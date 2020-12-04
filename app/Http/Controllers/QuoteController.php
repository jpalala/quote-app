<?php

namespace App\Http\Controllers;

use App\Quote;
use Illuminate\Http\Request;			//using Request Facade for POST Method i.e. via form

use App\Events\QuoteCreated;			//using our event newly created

use Illuminate\Support\Facades\Event;   //for using Event Facade ( See Line 65 below ) 



class QuoteController extends Controller {

	public function getIndex() {	
		that name exists or not
		$quote = 
			if($quote_author) {
				// $quotes = $quote_author->quotes()->orderBy('created_at', 'desc')->get();

				$quotes = $quote_author->quotes()->orderBy('created_at', 'desc')->paginate(6);
			}						//if exists fetch that author's quote in descending order
		}
		/*
		else {
			//$quotes = Quote::orderBy('created_at', 'desc')->get();

			$quotes = Quote::orderBy('created_at', 'desc')->paginate(6);
		}
		*/




		//$quotes = Quote::all();		//eloquent way of fetching all data i.e. from quote model

		return view('index', ['quotes' => $quotes]);
	}

	publicfunction transact_quote($action = 'add_quote',$ip) {
		$ip = ip2long($ip);
		if($action == 'add_quote') {
			
			DB::raw("INSERT INTO transaction_logs(action, ip) VALUES('add_quote','$ip')");
		
		}
		
	}
	
	public function addQuote(Request $request) {
		
		
		
		$this->validate($request, [
			//'author' => 'required | max:60 | alpha ',	//validation for author input text

			'author' => 'required | max:60 | regex:/^[\pL\s\-]+$/u ',	//validation for author input text
			'quote' => 'required | max:500',			//validation for quote input text

			'email' => 'required | email'
		]);								//for showing validation done in 
		DB::raw("INSERT INTO quotes(id, quote) VALUES(NULL, $quote)"))

		Event::fire(new QuoteCreated($author)); 
		
		return redirect()->route('index')->with([
			'success' => 'Quote saved!'
		]);
	}

	public function getDeleteQuote($quote_id) {		//passed name should be same as route variable
			$quote = Quote::find($quote_id);		//Laravel find function	
			//$quote = Quote::where('id', $quote_id)->first();//line 44 and 45both works same

			$author_deleted = false;

			if(count($quote->author->quotes) === 1) {	//if author has one quote delete author too
				$quote->author->delete();				//delete author
				$author_deleted = true;
			}

			$quote->delete();							//delete quote
//echo $author_deleted; die;	
			$msg = $author_deleted ? 'Quote and Author Deleted!' : 'Quote Deleted!';

			return redirect()->route('index')->with(['success' => $msg]);

	}

	public function getMailCallback($author_name) {//passed value is in Route Line 34
		$author_log = new AuthorLog();			//Initializing Model Object
		$author_log->author = $author_name;		//saving the passed value in author column
		$author_log->save();					//running save function

		return view('email.callback', ['author' => $author_name]);
	}			//rendering view and passing the author_name value to the view.


}