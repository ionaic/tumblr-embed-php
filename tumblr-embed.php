<?php
	$url_base = "https://api.tumblr.com/v2/blog/";
	$blog_url = "rocket-penguin.tumblr.com";
	$api_key = "sqMpPQJKHzMJBgVbxNBhi7kgM4ISzPstxKYNLlKbbUZIG4Ewfy";
	$post_num = 10;
	$full_url = $url_base . $blog_url . "/posts/?api_key=" . $api_key . "&limit=" . $post_num;
	$json_str = file_get_contents($full_url);
	$json = json_decode($json_str, false, 100);
	writePosts($json->response->posts);
	
	function writePosts($posts) {
		foreach ($posts as &$post) {
			echo writeBasePost($post);
		}
		//TODO add a "see more at my blog" button at the bottom
	}
	function writeBasePost($post) {
		// should make a basic html div with a title span containing post title that links to the tumblr post and a div to contain the content
		
		$body = "<div class=\"tumblr-content\">";
		if ($post->type === "text") {
			$body = $body . writeTextPost($post);
		}
		else if ($post->type === "photo") {
			$body = $body . writePhotoPost($post);
		}
		else if ($post->type === "quote") {
			$body = $body . writeQuotePost($post);
		}
		else if ($post->type === "link") {
			$body = $body . writeLinkPost($post);
		}
		else if ($post->type === "answer") {
			$body = $body . writeAnswerPost($post);
		}
		else if ($post->type === "video") {
			$body = $body . writeVideoPost($post);
		}
		else if ($post->type === "audio") {
			$body = $body . writeAudioPost($post);
		}
		else if ($post->type === "chat") {
			$body = $body . writeChatPost($post);
		}
		$body = $body . "</div>";
		
		$tags = "<div class=\"tumblr-tags\">";
		foreach ($post->tags as &$tag) {
			$tags = $tags . "<span class=\"tumblr-tag\">" . $tag . "</span>";
		}
		$tags = $tags . "</div>";
		
		$date = getPostDate($post);
		
		return "<div class=\"tumblr-post\">" . $body . $tags . $date . "</div>";
	}
	function writeTextPost($post) {
		$title = getPostTitle($post);
		return $title . "<div class=\"tumblr-post-body\">" . $post->body . "</div>";
	}
	function writePhotoPost($post) {
		$photos = getPhotos($post);
		
		$caption = getCaption($post);
		
		return $photos . $caption;
	}
	function writeQuotePost($post) {
		$quote = "<div class=\"tumblr-quote-text\">" . $post->text . "</div>";
		$source = "<div class=\"tumblr-quote-source\">" . $post->source . "</div>";
		return $quote . $source;
	}
	function writeLinkPost($post) {
		$title = getPostTitle($post);
		$photos = getPhotos($post);
		$link ="<a href=\"" . $post->url . "\" class=\"tumblr-link\"><span class=\"tumblr-url\">" . $post->publisher . "</span> <span class=\"tumblr-title\">" . $title . "</span><span class=\"tumblr-description\">" . $post->excerpt . "</span>" . $photos . "</a>";
		$caption = getCaption($post);
		return $link . $caption;
	}
	function writeChatPost($post) {
		$title = getPostTitle($post);
		//$body = "<div class=\"tumblr-post-body\">" . $post->body . "</div>";
		$body = "<div class=\"tumblr-post-body\">";
		foreach ($post->dialogue as &$msg) {
			$body = $body . "<div class=\"tumblr-chat-line\"><span class=\"tumblr-chat-label\">" . $msg->label . "</span><span class=\"tumblr-chat-text\">" . $msg->phrase . "</span></div>";
		}
		$body = $body . "</div>";
		return $title . $body;
	}
	function writeAudioPost($post) {
		$player = getPlayer($post);
		$caption = getCaption($post);
		return $player . $caption;
	}
	function writeAnswerPost($post) {
		$ask = "<a class=\"tumblr-question-link\" href=\"". $post->asking_url ."\"><span class=\"tumblr-question-name\">" . $post->asking_name . "</span></a>";
		$question = "<div class=\"tumblr-question\"><span class=\"tumblr-question-body\">" . $post->question . "</span>" . $ask . "</div>";
		$answer = "<div class=\"tumblr-answer\">" . $post->answer . "</div>";
		return $question . $answer;
	}
	function writeVideoPost($post) {
		$player = getPlayer($post);
		$caption = getCaption($post);
		return $player . $caption;
	}
	function getPostTitle($post) {
		if (property_exists($post, 'title')) {
			//$title = "<a class =\"tumblr-link\" href=" . $post->post_url . "<span class=\"tumblr-title\">" . $post->title . "</span></a>";
			$title = "<span class=\"tumblr-title\">" . $post->title . "</span>";
			return $title;
		}
	}
	function getPhotos($post) {
		if (property_exists($post, 'photos')) {
			$photos = "<div class=\"tumblr-gallery\">";
			foreach ($post->photos as &$photo) {
				$photos = $photos . "<img class=\"tumblr-image\" src=\"" . $photo->alt_sizes[0]->url . "\"></img>";
			}
			$photos = $photos . "</div>";
			return $photos;
		}
	}
	function getCaption($post) {
		if (property_exists($post, 'caption')) {
			return "<div class=\"tumblr-caption\">" . $post->caption . "</div>";
		}
		else if (property_exists($post, 'description')) {
			return "<div class=\"tumblr-caption\">" . $post->description . "</div>";
		}		
	}
	function getPlayer($post) {
		if (property_exists($post, 'player')) {
			if (is_array($post->player)) {
				return "<div class=\"tumblr-player\">" . end($post->player)->embed_code . "</div>";
			}
			else {
				return "<div class=\"tumblr-player\">" . $post->player . "</div>";
			}
		}
	}
	function getPostDate($post) {
		$time = strtotime($post->date);
		return "<a href=\"" . $post->post_url . "\" class=\"tumblr-post-link\" target=\"_blank\"><div class=\"tumblr-date\">" . date("M d, Y", $time) . "</div></a>";
	}
?>