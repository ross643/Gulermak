<?php
// scripts/create-block.php

// 1. Helper function to ask questions
function ask($question, $default = '')
{
	$prompt = $question;
	if ($default) {
		$prompt .= " [$default]";
	}
	echo $prompt . ": ";
	$handle = fopen("php://stdin", "r");
	$line = fgets($handle);
	fclose($handle);
	$result = trim($line);
	return empty($result) ? $default : $result;
}

// 2. Helper to slugify strings (e.g. "Hero Banner" -> "hero-banner")
function slugify($text)
{
	$text = preg_replace('~[^\pL\d]+~u', '-', $text);
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	$text = preg_replace('~[^-\w]+~', '', $text);
	$text = trim($text, '-');
	$text = strtolower($text);
	return $text;
}

function camelCase($text)
{
	$text = str_replace(' ', '',
		ucwords(str_replace(['-', '_'],
			' ', $text))
	);

	return $text;
}

echo "\n🚀  Fishtank Block Scaffolder\n";
echo "------------------------------\n";

// --- GATHER INPUTS ---

// Name
$humanName = ask("Block Name (e.g. Hero Header)");
if (empty($humanName)) {
	die("Error: Block name is required.\n");
}

$slug = slugify($humanName);

// Questions defined in your prompt
$needsJs = strtolower(ask("Does it need Javascript? (y/n)", "n")) === 'y';
$needsComposer = strtolower(ask("Does it need a Composer file? (y/n)", "n")) === 'y';
$allowMargin = strtolower(ask("Allow top/bottom Margin? (y/n)", "y")) === 'y';
$allowPadding = strtolower(ask("Allow top/bottom Padding? (y/n)", "y")) === 'y';
$innerBlocks = strtolower(ask("Will this use InnerBlocks? (y/n)", "n")) === 'y';
$acfVersion = ask("Use ACF Block Version 2 or 3?", "2");

// --- PREPARE DATA ---

$namespace = 'fishtank'; // Change this to your theme namespace
$blockPath = __DIR__ . "/../../resources/views/blocks/{$slug}"; // Assumes blocks are in theme-root/blocks/

// Basic block.json structure
$blockJson = [
	'name' => "{$namespace}/{$slug}",
	'title' => $humanName,
	'description' => "A custom {$humanName} block.",
	'style' => "{$namespace}/{$slug}",
	'script' => $needsJs ? "{$namespace}/{$slug}" : false,
	'category' => 'fishtank',
	'icon' => 'superhero',
	'apiVersion' => "3",
	'acf' => [
		'blockVersion' => $acfVersion,
		'hideFieldsInSidebar' => $acfVersion === "3" ? true : false,
		'mode' => 'preview',
		"renderCallback" => "\\App\\acf_block_render_callback",
	],
	'supports' => [
		'jsx' => true,
		"multiple" => true,
		"align" => false,
		"anchor" => true,
		"alignContent" => false,
		"html" => false,
		"ariaLabel" => true,
		"color" => [
			"text" => false,
			"background" => false,
			"link" => false,
		],
		"alignText" => false,
		'anchor' => true,
		"typography" => [
			"lineHeight" => false,
			"fontSize" => false,
		],
		"fullHeight" => false,
		'spacing' => [
			'margin' => $allowMargin ? ['top', 'bottom'] : false,
			'padding' => $allowPadding ? ['top', 'bottom'] : false,
		],
	],
	"example" => [
		"attributes" => [
			"mode" => "preview",
			"data" => [
				"is_preview" => true,
			],
			"style" => [
				"type" => "object",
				"default" => [
					"spacing" => [
						"padding" => [
							"top" => "var:preset|spacing|md",
							"bottom" => "var:preset|spacing|md",
						],
					],
				],
			],
		],
	],
];

if ($innerBlocks) {
	$blockJson['allowedBlocks'] = [
		"core/heading",
	];
	$blockJson['example']['attributes']['template'] = [
		'core/heading',
		[
			'level' => 2,
			'placeholder' => 'Heading',
			'fontSize' => 'large',
		],
	];
}

// --- CREATE FILES ---

if (is_dir($blockPath)) {
	die("\n❌  Error: Block '{$slug}' already exists.\n");
}

mkdir($blockPath, 0755, true);

// 1. Create block.json
file_put_contents(
	"$blockPath/block.json",
	json_encode($blockJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

// 2. Create CSS
$scssContent = "@use '@styles/utils' as *;\n\n.wp-block-fishtank-{$slug} {\n    // Styles for {$humanName}\n}\n";
file_put_contents("$blockPath/{$slug}.scss", $scssContent);

// 3. Create PHP Blade Render Template
$bladeContent = "<section @include('partials.block-attributes')>\n\t<div class=\"inner container--{{ isset(\$fields['container_size']) ? \$fields['container_size'] : 'md' }}\">\n\t";
$bladeContent .= $innerBlocks ? "<InnerBlocks allowedBlocks=\"{{ wp_json_encode(\$block['allowed_blocks']) }}\" template=\"{{ wp_json_encode(\$block['example']['attributes']['template']) }}\" />\n" : "\t\n";
$bladeContent .= "</div>\n</section>";
file_put_contents("$blockPath/{$slug}.blade.php", $bladeContent);

// 4. Create JS (Optional)
if ($needsJs) {
	file_put_contents("$blockPath/{$slug}.js", "(function($){\n // JS for {$humanName}\n})(jQuery);");
}

// 5. Create Composer.json (Optional)
if ($needsComposer) {
	$composerPath = __DIR__ . "/../View/Composers";

	$composerNamespace = camelCase($humanName);

	$composerContent = "<?php\n\nnamespace App\View\Composers;\n\nuse Roots\Acorn\View\Composer;\n\nclass {$composerNamespace} extends Composer\n{\n\t/**\n\t * List of views served by this composer.\n\t *\n\t * @var array\n\t */\n\tprotected static \$views = [\n\t\t'blocks.{$slug}.{$slug}',\n\t];\n\n\t/**\n\t * Data to be passed to view before rendering.\n\t *\n\t * @return array\n\t */\n\tpublic function with()\n\t{\n\t\treturn [\n\t\t\t 'data_key' => \$this->getDataMethod(),\n\t\t];\n\t}\n\n\t/**\n\t * Example data method.\n\t *\n\t * @return mixed\n\t */\n\tpublic function getDataMethod()\n\t {\n\t \treturn 'data_value';\n\t}\n}\n";
	file_put_contents(
		"$composerPath/$composerNamespace.php",
		$composerContent
	);
}

echo "\n✅ Success! Block created at: resources/View/blocks/{$slug}\n";
