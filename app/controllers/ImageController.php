<?php 

class ImageController extends BaseController {

	public function getIndex () {
		return View::make('tpl.index');
	}

	public function postIndex() {
		//首先是验证文件是否符合规则

		$validation = Validator::make(Input::all(),Images::$upload_rules);

		// 如果验证失败，则重定向到首页并提示错误信息

		if($validation->fails()) {
			return Redirect::to('/') 
				->withInput()
				->withErrors($validation);
		}
		else {
			// 如果通过则上传文件

			$image = Input::file('image');

			// 通过上传文件的名称来获取文件名

			$filename = $image->getClientOriginalName();

			$filename = pathinfo($filename,PATHINFO_FILENAME);

			// 对于文件外面还可以做好一点，在文件名前添加8位数的随机数字，这样也可以确保文件名称的唯一性

			$fullname = Str::slug(Str::random(8).$filename).'.'.
				$image->getClientOriginalExtension();

			//上传完文件后，我们还要创建缩略图并移动到thumbs文件夹

			$upload = $image->move
				(Config::get('image.upload_folder'),$fullname);

			// 下面是调用先前下载好第三方的开发包来处理缩略图方面的工作

			Image::make(Config::get('image.upload_folder').'/'.$fullname)
			->resize(Config::get('image.thumb_width'),null,true)
			->save(Config::get('image.thumb_folder').'/'.$fullname);

			// 如果文件是已经上传了的，则提示信息给用户。反之则提示成功的上传的信息。

			if($upload) {
				//上传完图片把相关信息插入到数据库中
				$insert_id = DB::table('photos')->insertGetId(
				    array(
				    	'title' => Input::get('title'),
				    	'image' => $fullname
				    )
				);

				//重定向对应图片链接的页面
				return Redirect::to(URL::to('snatch/'.$insert_id))
					->with('success','Your image is uploaded successfully!');
			} else {
				//提示错误信息
				return Redirect::to('/')
					->withInput()
					->with('error','Sorry, the image could not be uploaded, please try again later');
			}
		}
	}

	public function getSnatch($id) {

		// 从数据中查询图片的id
		$image = Images::find($id);

		// 如果找到，就加载视图，否则就返回到主页面并提示错误信息

		if($image) {
			return View::make('tpl.permalink')
				->with('image',$image);
		} else {
			return Redirect::
				to('/')->with('error','Image not find');
		}

	}

	public function getAll() {
		
		//获取所有的图片，当数据超过指定的条目的时候使用分页
		$all_images = DB::table('photos')->orderBy('id','desc')->paginate(6);

		// 然后在视图中渲染数据并显示出来

		return View::make('tpl.all_images')
			->with('images',$all_images);

	}

	public function getDelete($id) {

		// 找到要删除图片的信息
		$image = Images::find($id);

		// 如果存在，则执行下面的代码

		if($image) {
			// 首先是删除服务端存放的图片文件

			File::delete(Config::get('image.upload_folder').'/'.$image->image);
			File::delete(Config::get('image.thumb_folder').'/'.$image->image);

			// 然后再删除数据中的相关的记录

			$image->delete();

			// 重定向到主页并提示用户成功删除

			return Redirect::to('/')
				->with('success','Image deleted successfully');
		} else {
			//如果没有查询到图片，就重定向到主页并提示错误信息

			return Redirect::to('/')
				->with('error','No image with given ID found');
		}

	}
}