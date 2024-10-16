<?php

	function enqueue_stuff() {

		$templatedir = get_template_directory_uri();
		$enqueList = [	
			[
				"name" => 'FontAwesome.css', 
				"type" => 'css',
				"path" => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
				"version" => '4.7.0_defer'
			],
			[
				"name" => 'style.css', 
				"type" => 'css',
				"path" => $templatedir . '/style.css',
				"version" => filemtime(get_theme_file_path('/style.css'))
			],
			[
				"name" => 'slick.css', 
				"type" => 'css',
				"path" => 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css',
				"version" => '1.8.0_defer'
			],
			[
				"name" => 'slick.theme.css', 
				"type" => 'css',
				"path" => 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css',
				"version" => '1.8.0_defer'
			],
			[
				"name" => 'jquery.js', 
				"type" => 'js',
				"path" => 'https://code.jquery.com/jquery-3.7.1.js',
				"version" => '3.3.1',
				"loadInFooter" => false
			],
			
			[
				"name" => 'slick.js', 
				"type" => 'js',
				"path" => 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js',
				"version" => '1.8.1',
				"loadInFooter" => true
			],
			[
				"name" => 'custom.js', 
				"type" => 'js',
				"path" => $templatedir . '/js/custom.js',
				"version" => '1.0.0',
				"loadInFooter" => true
			],
			[
				"name" => 'TweenMax.js', 
				"type" => 'js',
				"path" => 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js',
				"version" => '2.1.2_defer',
				"loadInFooter" => true
			],
			[
				"name" => 'hammer.js', 
				"type" => 'js',
				"path" => 'https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js',
				"version" => '2.0.8_defer',
				"loadInFooter" => true
			],
			[
				"name" => 'gsap.js', 
				"type" => 'js',
				"path" => 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.6.1/gsap.min.js',
				"version" => '1.0.0',
				"loadInFooter" => false
			],
			[
				"name" => 'magnific-popup.css', 
				"type" => 'css',
				"path" => 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css',
				"version" => '1.0.0',
				"loadInFooter" => false
			],
		];
		
		foreach($enqueList as $asset) {	
			if ($asset['type'] === 'css') {
				wp_enqueue_style( 
					'victor_'.$asset['name'],  	// handle
					$asset['path'], 			// src
					null, 						// deps
					$asset['version'] 			// ver
				);	
			}	
			if ($asset['type'] === 'js') {
				wp_enqueue_script( 
					'victor_'.$asset['name'],  	// handle
					$asset['path'], 			// src
					array(), 					// deps
					$asset['version'], 			// ver
					$asset['loadInFooter']		// in footer
				);	
			}
		}
	} 
	
	add_action( 'wp_enqueue_scripts', 'enqueue_stuff' );
	
	
	// Function to defer or asynchronously load scripts for SEO Performance
	
	function js_async_attr($tag){	
		if (true == strpos($tag, 'defer') ) {
			 return str_replace( ' src', '  defer="defer" src', $tag ); 
		}
		return $tag;
	}
	add_filter( 'script_loader_tag', 'js_async_attr', 1 );


?>
