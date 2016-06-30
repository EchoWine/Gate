<?php

namespace Test\Controller;

use CoreWine\DataBase\DB;
use CoreWine\Router;

use CoreWine\SourceManager\Controller as Controller;


class TestDataBaseController extends Controller{
	
	/**
	 * Set all Routers
	 */
	public function __routes(){

		$this -> route('index') -> url("/test/db");

	}

	/**
	 * Set index
	 */
	public function index(){

		DB::connect([
			'driver' => 'mysql',
			'hostname' => '127.0.0.1',
			'database' => 'wine',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'restore' => 0,
			'alter_schema' => true,
		]);


		DB::schema('tab1',function($tab){
			$tab -> id();
			$tab -> string('Name') -> unique();
			$tab -> string('foo') -> null();
			$tab -> string('foDo') -> default('abcde') -> null();
			$tab -> int('fo1os');
		});

		DB::startLog();
		DB::schema('tab2') -> id() -> alter();
		DB::schema('tab2') -> bigint('tab1_id') -> foreign('tab1','id') -> alter();

		DB::schema('tab3') -> id() -> alter();
		DB::schema('tab3') -> bigint('tab1_id') -> foreign('tab1','id') -> alter();
		DB::schema('tab3') -> string('username') -> unique() -> alter();

	 	DB::schema('tab3_tab2') -> bigint('tab3_id') -> foreign('tab3','id') -> alter();
	 	DB::schema('tab3_tab2') -> bigint('tab2_id') -> foreign('tab2','id') -> alter();
	 	DB::schema('tab3_tab2') -> bigint('taxi') -> alter();

	 	$tab1_id = DB::table('tab1') -> insert([
			['name' => md5(microtime()),'foo' => null],
			['name' => md5(microtime()),'foo' => null],
			['name' => md5(microtime()),'foo' => '123']
		]);
	 	
	 	$tab2_id = DB::table('tab2') -> insert(['tab1_id' => $tab1_id[0]]);
	 	$tab3_id = DB::table('tab3') -> insert(['tab1_id' => $tab1_id[1],'username' => md5(microtime())]);
	 	DB::table('tab3_tab2') -> insert(['tab2_id' => $tab2_id[0],'tab3_id' => $tab3_id[0],'taxi' => 5]);


	 	DB::table('tab1') -> insert(['name' => md5(microtime())]);


	 	/* --------------------------------------

	 			JOIN

		--------------------------------------- */

	 	DB::table('tab2') -> join('tab3_tab2','tab3_tab2.tab2_id','=','tab2.id') -> join('tab3','tab3_tab2.tab3_id','=','tab3.id') -> get();


	 	DB::table('tab3_tab2') -> join(['tab3','tab2']) -> get();


	 	DB::table('tab2 as ttt') -> join('tab3_tab2') -> join('tab3') -> get();


	 	DB::table('tab2 as tb2')
	 	-> join('tab3_tab2 as tb32',function($q){

	 		$q = $q -> where('tb32.taxi','=',5);
	 		return $q;

	 	}) -> join('tab3') -> get();

	 	
		DB::table('tab2')
	 	-> join('tab3_tab2',function($q){
	 		$q = $q -> on('tab3_tab2.tab2_id','=','tab2.id');
	 		$q = $q -> where('tab3_tab2.taxi','=',5);
	 		return $q;

	 	}) -> join('tab3') -> get();
	 	
	 	DB::table('tab3_tab2') -> join(['tab3' => function($q){
	 		$q = $q -> where('tab3_tab2.taxi','=',5);
	 		return $q;
	 	},'tab2']) -> get();


		DB::table('tab2')
	 	-> join('tab3_tab2',function($q){
	 		$q = $q -> on(function($q){
	 			return $q -> orOn('tab3_tab2.tab2_id','=','tab2.id') -> orOn('tab3_tab2.tab2_id','=','tab2.id');
	 		});
	 		$q = $q -> on(function($q){
	 			return $q -> orOn('tab3_tab2.tab2_id','=','tab2.id') -> orOn('tab3_tab2.tab2_id','=','tab2.id');
	 		});
	 		$q = $q -> where('tab3_tab2.taxi','=',5);
	 		return $q;

	 	}) -> join('tab3') -> get();
	 	
	 	DB::table('tab2')
	 	-> crossJoin('tab3_tab2')
	 	-> join('tab3')
	 	-> get();
	 	
	 
	 	/*
	 	DB::table('tab1') -> insert(function(){
	 		return DB::table('tab1') -> select('name');
	 	});
	 	*/

		/*
		DB::schema('tab3') -> dropColumn('username');
		DB::schema('tab3') -> drop();
		*/

		DB::table(function(){
			return DB::table(function(){
				return DB::table('tab1');
			},'w43');
		})
		-> union(DB::table('tab1'))
		-> get();

		DB::table('tab1 as r')
		-> orWhere(function($q){
			$q = $q -> orWhere('foo','123');
			$q = $q -> orWhereIn('foo',['123']);
			$q = $q -> orWhereLike('foo','%123%');
			$q = $q -> orWhereNull('foo');
			$q = $q -> orWhereNotNull('foo');
			$q = $q -> orWhereNotBetween('foo',[1,10]);
			$q = $q -> orWhereBetween('foo',[1,10]);
			return $q;
		})
		-> orWhere(function($q){
			$q = $q -> where('foo','123');
			$q = $q -> whereIn('foo',['123']);
			$q = $q -> whereLike('foo','%123%');
			$q = $q -> whereNull('foo');
			$q = $q -> whereNotNull('foo');
			$q = $q -> whereNotBetween('foo',[1,10]);
			$q = $q -> whereBetween('foo',[1,10]);
			return $q;
		})
		-> orHaving(function($q){
			$q = $q -> orHaving('foo','123');
			$q = $q -> orHavingIn('foo',['123']);
			$q = $q -> orHavingLike('foo','%123%');
			$q = $q -> orHavingNull('foo');
			$q = $q -> orHavingNotNull('foo');
			$q = $q -> orHavingNotBetween('foo',[1,10]);
			$q = $q -> orHavingBetween('foo',[1,10]);
			return $q;
		})
		-> orHaving(function($q){
			$q = $q -> having('foo','123');
			$q = $q -> havingIn('foo',['123']);
			$q = $q -> havingLike('foo','%123%');
			$q = $q -> havingNull('foo');
			$q = $q -> havingNotNull('foo');
			$q = $q -> havingNotBetween('foo',[1,10]);
			$q = $q -> havingBetween('foo',[1,10]);
			return $q;
		})
		-> get();

		DB::table('tab1') -> count();
		DB::table('tab1') -> max('id');
		DB::table('tab1') -> min('id');
		DB::table('tab1') -> avg('id');

		DB::table('tab1') -> where('id',1) -> update('foo','bla');
		DB::table('tab1') -> where('id',1) -> update(['foo' => 'bla']);

		DB::table('tab1') -> where('id',1) -> update(
		[
			['id','foo']

		],
		[
			[
				0 => 'bla',
				1 => 'cia'
			]
		]
		);


		// End all schema
		DB::dropMissing();
		DB::printLog();

		die();

	}

}

?>