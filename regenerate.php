<?php

define('ROOT', __DIR__ . '/i/');  // Root folder for images
define('WWW', '/i/');
define('CMD_CF', ' -sampling-factor 4:2:0');
define('CMD_CS', ' -colorspace sRGB');
define('CMD_IL', ' -interlace JPEG');

$Q = [75, 80, 85];
$F = ['z' => 'None', 'i' => 'Interlace', 's' => 'Sampling factor', 'si' => 'Sampling factor + Interlace'];
$S = [[300, 200], [900, 600], [1600, 1067], [1800, 1200]];

// 1. Read dirs
$root_dir = realpath(ROOT);

$data = [];
$max = [];
// Iterate files and fill data
foreach ($Q as $q) {

	$cmd = '-quality ' . $q . CMD_CS;
	$d1 = 'q' . $q;

	foreach ($F as $fe => $desc) {

		$d2 = $fe;
		$row = [];

		switch($fe) {
			case 'i':
				$f_cmd = $cmd . CMD_IL;
				break;
			case 'si':
				$f_cmd = $cmd . CMD_CF . CMD_IL;
				break;
			case 's':
				$f_cmd = $cmd . CMD_CF;
				break;
			default:
				$f_cmd = $cmd;
				break;
		}

		foreach ($S as $sz) {
			$w = $sz[0];

			// Filename
			$rel = $d1 . '/' . $d2 . '/' . $w . '.jpg';
			$fnm = $root_dir . '/' . $rel;
			$fsz = filesize($fnm);
			$url = WWW . $rel;

			$row[] = [
				'url' => $url,
				'sz' => $fsz,
				'tip' => 'File size: ' . $fsz . ' bytes',
				'w' => $w,
			];

			if($fsz > ($max[$w] ?? 0)) {
				$max[$w] = $fsz;
			}
		}
		$data[] = [
			'q' => $q,
			'qt' => 'Quality: ' . $q,
			'fe' => $fe,
			'fd' => $desc,
			'tip' => $f_cmd,
			'r' => $row
		];

	}
}

$h1 = 'Testing ImageMagick conversion rate for JPEG photos';
$meta_desc = $h1 . ', ' . date('Y-m-d')
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8"/>
	<title><?= $h1 ?></title>
	<meta name="keywords" content="imagemagick, google pagespeed insights"/>
	<meta name="description" content="<?= $meta_desc ?>"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
	<style>
		table td { text-align: right; }
		table thead th {text-align: center; }
		table td.t { text-align: left; }
	</style>

</head>
<body>
<section class="section">
<div class="container">
	<h1 class="title is-3"><?= $h1 ?></h1>
<table class="table">
	<thead>
		<th>Quality</th>
		<th>Settings</th>
	<?php foreach($S as $sz ) { ?>
		<th colspan="2"><?= $sz[0] . 'x' . $sz[1] ?></th>
	<?php }	?>
		<th colspan="2">total</th>
	</thead>
	<tbody>
		<tr class="max">
			<td class="t" colspan="2">Maximum (worst)</td>
			<?php
			$total = 0;
			foreach($max as $x ) { $total += $x; ?>
			<td><?= $x ?></td>
				<td class="small">%</td>
			<?php }	?>
			<td><?= $total ?></td>
			<td class="small">%</td>
		</tr>
		<?php foreach($data as $d ) {
			$row = $d['r']; ?>
		<tr>
			<td>
				<?= $d['q'] ?>
				<span class="tag is-light" title="<?= $d['tip']?>">?</span>
			</td>
			<td class="t"><?= $d['fd'] ?></td>
			<?php
			$total = 0;
			$percents = 0;
			foreach($row as $res ) {
				$sz = (int) $res['sz'];
				$total += $sz;
				$w = (int) $res['w'];
				$m = $max[$w];
				$perc = 100.0 - round($sz *100.0 / $m, 2);
				$percents += $perc;
			?>
				<td>
					<a title="<?= $res['tip'] ?>" target="_blank" href="<?= $res['url'] ?>"><?= $sz ?></a>
				</td>
				<td>
					<?= sprintf('%01.2f', $perc) ?>
				</td>
			<?php }
			$pa = round($percents / count($S), 2);
			?>
			<td><?= $total ?></td>
			<td><?= sprintf('%01.2f', $pa) ?></td>
		</tr>
		<?php }	?>
	</tbody>
</table>
</div>
</section>
</body>
</html>
