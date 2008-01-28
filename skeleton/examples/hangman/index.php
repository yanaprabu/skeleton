<?php

// Hangman game example ported from WACT hangman example and refactored to
// follow MVC pattern.

include_once 'config.php';

include_once 'A/Locator.php';
include_once 'A/Http/Request.php';
include_once 'A/Http/Response.php';
include_once 'A/Template/Strreplace.php';
include_once 'A/Rule.php';
include_once 'A/Filter.php';
include_once 'A/Controller/Input.php';
include_once 'A/Controller/App.php';

//-----------------------------------------------------------------------------

class HangmanGame {
    var $word;
    var $level;
    var $levels;
    var $guesses = '';
    var $misses = 0;
    var $feedback = '';
    var $availableLetters;

    function HangmanGame() {
    	$this->levels  = $this->getLevels();
    }
    
    function getLevels() {
    	return array('easy' => 10, 'medium' => 5, 'hard' => 3);
    }
    
    function pickRandomWord() {
        $words = preg_split("/[\s,]+/", file_get_contents('constitution.txt'));
        do {
            $i = rand(0, count($words)-1);
            $word = $words[$i];
        } while(strlen($word) < 5 || !preg_match('/^[a-z]*$/i', $word));
        $this->word = strtoupper($word);
    }
    
    function guess($letter) {

		if ($letter) {
	        $this->guesses .= $letter;
	        if (!is_integer(strpos($this->word, $letter))) {
	            $this->misses++;
	        }
        }
		$this->feedback = '';
        for ($i = 0; $i < strlen($this->word); $i++) {
            if (is_integer(strpos($this->guesses, $this->word{$i}))) {
                $this->feedback .= $this->word{$i};
            } else {
                $this->feedback .= '_';
            }
        }

        $this->availableLetters = array();
		for ($ch=65; $ch<=90; ++$ch) {
			$this->availableLetters[] = array(
				'letter' => chr($ch),
				'available' => (boolean) !is_integer(strpos($this->guesses, chr($ch))));
         }
    }
    
    function hasLost() {
        return ($this->misses >= $this->level);
    }

    function hasWon() {
        for ($i = 0; $i < strlen($this->word); $i++) {
            if (!is_integer(strpos($this->guesses, $this->word{$i}))) {
                return FALSE;
            }
        }
        return TRUE;
    }
 
}

class StartView {

    function render($locator) {
		$response = $locator->get('Response');

		$game = $locator->get('Game');
		$game->pickRandomWord();
		
		$template = new A_Template_Strreplace('templates/start.html');
		$template->set('game_levels_easy', $game->levels['easy']);
		$template->set('game_levels_medium', $game->levels['medium']);
		$template->set('game_levels_hard', $game->levels['hard']);
		$template->set('misses', '0');
		$template->set('guesses', '');
		$template->set('word', $game->word);

		$response->set('Content', $template->render());
    }
}

class GameView {

    function render($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		$game = $locator->get('Game');

		foreach ($game->availableLetters as $letter) {
			$ch = $letter['letter'];
			if ($letter['available']) {
            	$list[] = "<a href=\"?misses={$game->misses}&guesses={$game->guesses}&word={$game->word}&level={$game->level}&letter=$ch\">$ch</a>";
			} else {
              	$list[] = $ch;
  			} 
		}
		
		$template = new A_Template_Strreplace('templates/game.html');
		$template->set('word', $game->word);
		$template->set('guesses', $game->guesses);
		$template->set('misses', $game->misses);
		$template->set('level', $game->level);
		$template->set('feedback', $game->feedback);
		$template->set('list', implode(' ', $list));

		$response->set('Content', $template->render());
    }

}

class WinView {

    function render($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$game = $locator->get('Game');

		$template = new A_Template_Strreplace('templates/win.html');
		$template->set('word', $request->get('word'));

		$response->set('Content', $template->render());
    }
}

class LoseView {

    function render($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$game = $locator->get('Game');

		$template = new A_Template_Strreplace('templates/lose.html');
		$template->set('word', $request->get('word'));

		$response->set('Content', $template->render());
    }
}

class WinRule extends A_Rule {

	function WinRule($game) {
		$this->game = $game;
	}
	
	function isValid($dataspace) {
		return $this->game->hasWon();
	}
}

class LoseRule extends A_Rule {

	function LoseRule($game) {
		$this->game = $game;
	}
	
	function isValid($dataspace) {
		return $this->game->hasLost();
	}
}

class Guess extends A_Rule {

	function isValid($dataspace) {
		$value = $dataspace->get('level');
		return in_array($value, HangmanGame::getLevels());
	}
}

class Hangman extends A_Controller_App {

    function run($locator) {
		$request = $locator->get('Request');
    	$response = $locator->get('Response');

		$response->setRenderer(new A_Template_Strreplace('templates/layout.html'));
    	
        $number_rule = new A_Filter_Regexp('/[^0-9]/');
        $letter_rule = new A_Filter_Regexp('/[^A-Z]/');
        
        $param = new A_Controller_InputParameter('level');
        $param->addFilter($number_rule);
        $this->addParameter($param);
        
        $param = new A_Controller_InputParameter('word');
        $param->addFilter($letter_rule);
        $this->addParameter($param);
        
        $param = new A_Controller_InputParameter('guesses');
        $param->addFilter($letter_rule);
        $this->addParameter($param);
        
        $param = new A_Controller_InputParameter('misses');
        $param->addFilter($number_rule);
        $this->addParameter($param);

        $letter = new A_Controller_InputParameter('letter');
        $letter->addFilter($letter_rule);
        $this->addParameter($letter);
        
		$this->processRequest($request);
	
		$game = new HangmanGame();
		$locator->set('Game', $game);
		
        $game->word = $request->get('word');
        $game->guesses = $request->get('guesses');
        $game->misses = $request->get('misses');
        $game->level = $request->get('level');
		$game->guess($letter->value);
		
		$this->addState(new A_Controller_App_State('start', new A_DL('', 'StartView', 'render')));
		$this->addState(new A_Controller_App_State('game', new A_DL('', 'GameView', 'render')));
		$this->addState(new A_Controller_App_State('win', new A_DL('', 'WinView', 'render')));
		$this->addState(new A_Controller_App_State('lose', new A_DL('', 'LoseView', 'render')));

		$this->addTransition(new A_Controller_App_Transition('start', 'lose', new A_Rule_NotNull('giveup', '')));
		$this->addTransition(new A_Controller_App_Transition('start', 'game', new A_Rule_NotNull('level', '')));
		$this->addTransition(new A_Controller_App_Transition('game', 'lose', new LoseRule($game)));
		$this->addTransition(new A_Controller_App_Transition('game', 'win', new WinRule($game)));
	
		parent::run($locator);
    }

}

//-----------------------------------------------------------------------------
$Locator = new A_Locator();
$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);
$controller = new Hangman('start');
$controller->run($Locator);
echo $Response->render($Locator);

?>