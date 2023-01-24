<?

require('../config.php');
$benchmark_start = microtime_float();

// make troubleshooting page
$source = CONTENTDIR."static";
$path = BASEDIR;

// update the files on the server via SVN
// look for the .subversion folder somewhere else
// otherwise will go looking for /home/root/.subversion or some other user

$where = CONTENTDIR . 'static';
putenv('HOME=' . CONTENTDIR);

//`cd $where && /usr/bin/svn update`;

// Switch from SVN to GIT, 14 FEB 2013
`cd $path && /usr/bin/git pull https://github.com/processing/processing-web/`;

// make troubleshooting page
$source = CONTENTDIR."static/";

$page = new Page("Overview", "Overview");
$page->content(file_get_contents($source."overview.html"));
writeFile('overview/index.html', $page->out());
#copydirr($source.'/images', $path.'/images');

$page = new Page("Foundation", "Foundation");
$page->content(file_get_contents($source."foundation.html"));
writeFile('foundation/index.html', $page->out());

$page = new Page("People", "People");
$page->content(file_get_contents($source."people.html"));
writeFile('people/index.html', $page->out());

$page = new Page("Books", "Books");
$page->content(file_get_contents($source."books.html"));
writeFile('books/index.html', $page->out());

// Copy over the errata file for Processing: A Programming Handbook...
copy($source.'processing-errata.txt', $path.'books/processing-errata.txt');
// Copy over the media.zip file for Getting Started with Processing...
copy($source.'media.zip', $path.'books/media.zip');

$page = new Page("Copyright", "Copyright");
$page->content(file_get_contents($source."copyright.html"));
writeFile('copyright.html', $page->out());

// Copy over the images for the shop index
if (!is_dir($path.'shop')) { 
	mkdir($path.'shop', 0757);
}

if (!is_dir($path.'shop/imgs')) { 
	mkdir($path.'shop/imgs', 0757); 
}

if (is_dir($path.'shop/imgs')) { 
	copydirr($source.'shop/imgs', $path.'shop/imgs', null, 0757, false);
}

$page = new Page("Shop", "Shop");
$page->content(file_get_contents($source.'shop/'."index.html"));
writeFile('shop/index.html', $page->out());

$benchmark_end = microtime_float();
$execution_time = round($benchmark_end - $benchmark_start, 4);

?>

<h2>Static page generation Successful</h2>
<p>Generated files in <?=$execution_time?> seconds.</p>