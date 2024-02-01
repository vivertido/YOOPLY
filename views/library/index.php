<style>
#gallery_wrapper {
	overflow: hidden;
	display: inline-block;
}

.gallery_item {
	float:left;
	overflow: hidden;
	width:240px;
	height:340px;
	background-color: #fff;
	//margin:10px 25px 25px 10px;
	padding:20px;
	border-radius:5px;
	border-style:solid;
	border-color:transparent;
}

.gallery_item:hover {
	background-color: #1CCFE7;
}
 
.gallery_item > a > img {
	opacity:0.8;
}
 
.gallery_image {
	display: block;
	margin: 0 auto;
	width:220px;
	height: 180px;
}

.gallery_caption {
	display: block;
	padding-top:20px;
	color:#663300;
	font-size: 1.2em;
}

.gallery_caption_desc {
	display: block;
	padding-top:20px;
	color:#5b5b5b;
	font-size: 1.0em;
	width:inherit;
}

.grow { 
	transition: all .2s ease-in-out;
}

.grow:hover { 
	transform: scale(1.08);
}
 
.uninstalled {
	background-color: orange;
	border-style:solid;
	border-color:transparent;
	border-radius:25px;
	margin-top:10px;
	width:30px;
	height:30px;
}

.uninstalled a {
	color:#fff;
	margin-top:-12px;
	font-size:2.3em;
	text-shadow:transparent;
}
 
.installed {
	background-color: #D2B48C;
	border-style:solid;
	border-color:transparent;
	border-radius:25px;
	margin-top:10px;
	width:30px;
	height:30px;
}

.installed a {
	color:#fff;
	font-size:2.3em;
	text-shadow:transparent;
}

.gallery_item a{
	text-decoration: none;
}
</style>

<center>
<h1>Form Gallery</h1>

<p>Select a Form to view report options and add to your Yooply </p>
<div id="gallery_wrapper">
	<?php foreach($resources as $resource): ?>
	<div class="gallery_item ui-shadow grow">
		<a href="/library/view/<?= $resource->libraryid ?>">
			<img class="gallery_image" src="/images/form_sample1.png" alt="" />
			<span class="gallery_caption"><?= $resource->title ?></span>
			<span class="gallery_caption_desc"><?= $resource->description ?></span>
		</a>
		<div class="<?= !is_null($resource->timeinstalled) ? 'uninstalled"><a href="/library/uninstall/'.$resource->libraryid.'">&#x2713;' : 'installed"><a href="/library/install/'.$resource->libraryid.'">+' ?></a></div>
	</div>
	<?php endforeach; ?>
</div>
</center>