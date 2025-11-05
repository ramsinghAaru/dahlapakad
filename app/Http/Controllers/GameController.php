<?php
namespace App\Http\Controllers;
use App\Models\{Game,Room,Move};
use Illuminate\Http\Request;

class GameController extends Controller{
  public function start(Request $r,$code){
    $room=Room::with('players')->whereCode($code)->firstOrFail();
    if($room->players->count()!==4) abort(422,'Need 4 players');
    $seats=['N','E','S','W'];
    $deck=$this->freshDeck(); shuffle($deck);
    $hands=array_fill_keys($seats,[]);
    foreach(range(1,5) as $_) foreach($seats as $s) $hands[$s][]=array_pop($deck);
    $dealer=$seats[array_rand($seats)]; $turn=$this->seatRightOf($dealer);
    $game=Game::create(['room_id'=>$room->id,'dealer_seat'=>$dealer,'turn_seat'=>$turn,'phase'=>'five_card_phase','deck'=>$deck,'hands'=>$hands]);
    return $game->fresh();
  }
  public function select(Request $r, Game $id){
    $g=$id; if($g->phase!=='five_card_phase') abort(422,'Wrong phase');
    $g->trump_suit=strtoupper($r->string('trump_suit'));
    $seats=['N','E','S','W'];
    foreach(range(1,2) as $_) foreach($seats as $s) foreach(range(1,4) as $__) $g->hands[$s][]=array_pop($g->deck);
    $g->phase='playing'; $g->save(); return $g->fresh();
  }
  public function play(Request $r, Game $id){
    $g=$id->fresh(); $seat=$r->string('seat'); $card=strtoupper($r->string('card'));
    if($g->turn_seat!==$seat) abort(409,'Not your turn');
    if(!in_array($card,$g->hands[$seat])) abort(422,'Card not in hand');
    $trick=$g->last_trick; $lead=$trick[0]['card'][1]??null; $cardSuit=$card[1];
    if($lead){ $hasLead=collect($g->hands[$seat])->contains(fn($c)=>$c[1]===$lead); if($hasLead && $cardSuit!==$lead) abort(422,'Must follow suit'); }
    $trick[]=['seat'=>$seat,'card'=>$card]; $g->last_trick=$trick; $g->hands[$seat]=array_values(array_diff($g->hands[$seat],[$card]));
    $next=$this->seatLeftOf($seat);
    if(count($trick)<4){ $g->turn_seat=$next; }
    else{
      [$winner,$wcard]=$this->resolveTrick($trick,$g->trump_suit);
      $g->centre_pile=array_merge($g->centre_pile,array_column($trick,'card'));
      $prev=$g->getAttribute('prev_winner');
      if($prev===$winner || $this->isLastTrick($g)){
        $team=in_array($winner,['N','S'])?'NS':'EW';
        $g->tricks_taken[$winner]=($g->tricks_taken[$winner]??0)+2;
        $tens=$this->countTens($g->centre_pile); $g->tens_taken[$team]+=$tens; $g->centre_pile=[]; $prev=null;
      } else { $prev=$winner; }
      $g->setAttribute('prev_winner',$prev); $g->last_trick=[]; $g->turn_seat=$winner;
    }
    $g->save(); Move::create(['game_id'=>$g->id,'seat'=>$seat,'card'=>$card,'trick_index'=>($g->moves()->count()+1)]);
    if($this->isLastTrick($g)){ $summary=$this->finalizeHand($g); return response()->json(['state'=>'hand_finished','summary'=>$summary]); }
    return $g->only(['id','turn_seat','last_trick','centre_pile','tricks_taken','tens_taken','hands']);
  }
  private function finalizeHand(Game $g): array{
    $ns=(int)($g->tens_taken['NS']??0); $ew=(int)($g->tens_taken['EW']??0);
    $seatTeam=fn(string $s)=>in_array($s,['N','S'])?'NS':'EW'; $dealerTeam=$seatTeam($g->dealer_seat);
    $winner=null; $kot=false; if($ns===4){$winner='NS';$kot=true;} if($ew===4){$winner='EW';$kot=true;}
    if(!$winner){ if($ns>$ew)$winner='NS'; elseif($ew>$ns)$winner='EW'; }
    $kots=$g->kots??['NS'=>0,'EW'=>0]; if($kot && $winner) $kots[$winner]=(int)$kots[$winner]+1;
    $next=$g->dealer_seat;
    if($winner){
      if($kot){ $next=($winner===$dealerTeam)?$this->seatRightOf($g->dealer_seat):$this->seatOppositeOf($g->dealer_seat); }
      else{ $next=($winner===$dealerTeam)?$this->seatRightOf($g->dealer_seat):$g->dealer_seat; }
    }
    $g->kots=$kots; $g->next_dealer_seat=$next; $g->phase='scoring'; $g->save();
    return ['winner_team'=>$winner,'tens'=>['NS'=>$ns,'EW'=>$ew],'won_kot'=>$kot,'next_dealer'=>$next,'kots'=>$kots];
  }
  private function freshDeck(): array{ $s=['S','H','D','C']; $r=['A','K','Q','J','T','9','8','7','6','5','4','3','2']; $d=[]; foreach($s as $S) foreach($r as $R) $d[]=$R.$S; return $d; }
  private function seatRightOf($x){ return ['N'=>'W','W'=>'S','S'=>'E','E'=>'N'][$x]; }
  private function seatLeftOf($x){  return ['N'=>'E','E'=>'S','S'=>'W','W'=>'N'][$x]; }
  private function seatOppositeOf($x){ return ['N'=>'S','S'=>'N','E'=>'W','W'=>'E'][$x]; }
  private function resolveTrick(array $trick, ?string $trump): array{ $lead=$trick[0]['card'][1]; $order=['A'=>13,'K'=>12,'Q'=>11,'J'=>10,'T'=>9,'9'=>8,'8'=>7,'7'=>6,'6'=>5,'5'=>4,'4'=>3,'3'=>2,'2'=>1]; $best=$trick[0]; $bestScore=-1; foreach($trick as $p){ $c=$p['card']; $rank=$c[0]; $suit=$c[1]; $isT=($trump && $suit===$trump); $isL=($suit===$lead); $score=($isT?100:0)+($isL?50:0)+$order[$rank]; if($score>$bestScore){$best=$p;$bestScore=$score;} } return [$best['seat'],$best['card']]; }
  private function countTens(array $cards): int{ return collect($cards)->filter(fn($c)=>$c[0]==='T')->count(); }
  private function isLastTrick(Game $g): bool{ $remaining=array_sum(array_map(fn($h)=>count($h),$g->hands)); return $remaining===0; }
}
