<?php 
	
class Images extends Eloquent {

	protected $table = 'photos';

	protected $fillable = array('title','image');

	public $timestamps = true;

	// 验证规则

	public static $upload_rules = array(

		'title' => 'required|min:3',
		'image' => 'required|image'

	);
}