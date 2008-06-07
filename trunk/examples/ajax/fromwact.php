<?php
 require_once '../loader.inc.php'; //loads common.inc.php
 require_once WACT_ROOT . 'controller/controller.new.php';
 require_once WACT_ROOT . 'view/view.new.php';
 
 class Application extends DecoratingController{
 	function Application(){
 		parent::DecoratingController();
 		
 		$responder = new PathInfoDispatchController();
 
                //the search command will handle the search logic
 		$searchCommand = new CommandController(new CallBack($this, 'search'));
 		$searchCommand->addParameter(new PostParameter('name'));
 		$responder->addPublicChild('search', $searchCommand);
 		
 		$this->addPublicChild('responder', $responder);
 		$this->setDefaultChildName('responder');
 		
 		$responder->addChild('searchResponse', new ViewController(new View('/results.html')));
 		
 		$this->setView(new AppView('/application.html'));
 	}
 	
 	function search(& $sender, & $request, & $responseModel){
 		$results = array(array('name' => 'Winnipeg'),
				 array('name' => 'Toronto'),
				 array('name' => 'Auckland'),
				 array('name' => 'Singapore'),
				 array('name' => 'Penang'));
		$results[] = array('name' => $request->getParameterValue('name'));
                //we're not doing any real filtering here, just sending content back to the client browser
 		$responseModel->set('results', $results);
                //returning searchResponse will redirect control to the ViewController in the new controller
                //architecture.
		return 'searchResponse';
	}
	
 }
 
 class AppView extends View{
 	function AppView($templateFile){
 		parent::View($templateFile);
 	}
 	
 	function prepareModel(& $source, & $request, & $responseModel){
		$responseModel->set('title', 'Application Controller');
		$responseModel->set('page_header', 'Demonstration of new WACT Controller Architecture');
	}
 }
 
 $app = new Application;
 $app->start();
 
?>
The script block below automagically performs the linking of the textbox(ac1), the Ajax request and the response container(ac1update). When the value of the textbox changes, the onchange event will be triggered and the Prototype framework will compose a complete POST request to ‘/samples/controllers/application.php/search’. ‘application.php/search’ will direct the request to the CommandController named ‘search’ that’s been registered as the child of the root PathInfoDispatchController.

<script type="text/javascript" language="javascript">
// <![CDATA[
    new Ajax.Autocompleter('ac1','ac1update','/samples/controllers/application.php/search');
// ]]>
</script>

The results array will be populated in results.html

<list:list from="results">
<ul>
<list:item>
<li>{$name}</li>
</list:item>
</ul>
</list:list>

The populated template will be sent back as the response to the POST request made by the autocompleter. Once the autocompleter receives the response from the server, it will insert the unordered list into the container specified (ac1update) as innerHTML.

And there you have it, Ajax goodness with WACT! 