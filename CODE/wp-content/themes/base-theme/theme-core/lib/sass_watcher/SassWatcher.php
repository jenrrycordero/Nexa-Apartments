<?php
/**
 * Original code copied from https://github.com/reyhoun/sass-watcher (author Reyhoun).
 *
 * Class to watch and generate a .scss file base on acf fields defined on the options page with specific structure.
 */

include_once "SassWatcherValidate.php";

class SassWatcher extends SassWatcherValidate
{
	private $scss_filename;
	private $scss_var_name;
	private $scss_dir;
	private $output_css_file;
	private $check_for_compile;
	private $prefix;

	/**
	 * SassWatcher constructor.
	 *
	 * @param bool   $check_for_compile Default true. Set this to check if the output directory is writable so the system can compile.
	 * @param string $scss_filename Filename of the output scss file. Default options. Do not include extension.
	 * @param string $css_filename Filename for the output css file. Default options. Do not include extension.
	 */
	public function __construct($check_for_compile = true, $scss_filename = "options", $css_filename = "options")
	{
		$this->scss_dir = WP_CONTENT_DIR . "/uploads/generated_scss";
		$this->scss_filename = $scss_filename . ".scss";
		$this->scss_var_name = $scss_filename . "_var";

		$this->output_css_file = theme_get_assets_directory() . "/style/$css_filename.css";

		$this->check_for_compile = $check_for_compile;

		$this->prefix = "s_";
	}

	/**
	 * TODO. make it work properly.
	 *
	 * @param string $append_css_var_file
	 * @param null   $format_style
	 * @param string $scss_folder
	 * @param string $scss_filename
	 * @param string $output_css
	 */
	public function compile($append_css_var_file = "", $format_style = null, $scss_folder = '', $scss_filename = '', $output_css = '')
	{

		if ( !$scss_folder ) $scss_folder = $this->scss_dir;
		if ( !$scss_filename ) $scss_filename = $scss_folder . "/" . $this->scss_filename;
		if ( !$output_css ) $output_css = $this->output_css_file;

		if ( $this->check_for_compile && !is_writable($output_css) ) return;

		require_once theme_get_core_directory() . "/lib/sass_watcher/scssphp/scss.inc.php";


		if( is_null($format_style) ) $format_style = new Leafo\ScssPhp\Formatter\Crunched();
		$scss_compiler = new Leafo\ScssPhp\Compiler();

		$scss_compiler->setImportPaths($scss_folder);
		$scss_compiler->setFormatter($format_style);

		try
		{
			$file = $this->get_full_name($this->scss_var_name, $append_css_var_file);
			$content = file_get_contents($file);
			$string_css = $scss_compiler->compile($content);

			file_put_contents($output_css , $string_css);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
	}

	/**
	 * This function evaluate the post to see if there is any ACF field with the proper structure to generate a sass file from them.
	 * The file is stored on the property $scss_dir/$scss_var_name.$append_css_var_file.scss. At the end the method <b>compile</b> is called, in case
	 * it cant be compiled then you should include the var name on the scss files manually.
	 *
	 * @param int $post_id post id to get the fields from to generate the field.
	 * @param string $append_css_var_file String to append to the output css file. This will be usefull when we have several
	 *                                    theme options scss definitions. It should be (as right now) the theme page slug.
	 */
	public function evaluateSavedPost($post_id, $append_css_var_file = "")
	{
		if ( 'options' !== $post_id ) return;

		$fields = get_field_objects('options');

		// load from post
		if( $fields )
		{
			$scss_string = $scss_string_repeater = '';
			foreach ( $fields as $key => $value )
			{
				$finfo = get_field_object( $key, $post_id, array( "load_value" => TRUE ) );

				if ( is_array( $finfo ) && substr( $finfo['name'], 0, 2 ) == $this->prefix )
				{
					$type = $finfo['type'];

					switch ( $type ):

						case 'repeater':
							// $scss_string .= chr(13);
							if ( empty( $finfo['value'] ) )
							{
								$scss_string .= '$' . $finfo['name'] . ': null;' . chr( 13 );
							}
							else
							{
								$i = 1;
								$pvar = '$' . $finfo['name'] . ': (' . count( $finfo['value'] ) . '), (';
								$prx = '$' . $finfo['name'] . '_';
								foreach ( $finfo['value'] as $row )
								{
									// Uncomment below line to create an id for each skin:
									// $skin_name = $finfo['name'] . '_' . $i;

									$pvar .= $prx . $i . ', ';
									$rvar = $prx . $i ++ . ': ';

									// Uncomment below line to create an id for each skin:
									// $rvar .= $skin_name . ', ';

									foreach ( $row as $key => $val )
									{
										if ( empty( $val ) )
										{
											$val = 'null';
										}

										// If name of field have 'ns_' prefix this field don't display in sass settings.
										if ( substr( $key, 0, 3 ) != 'ns_' )
										{
											if ( $key == 'skin_name' )
											{
												$rvar .= sanitize_title( $val ) . ', ';
											}
											else if ( substr( $key, 0, 3 ) == 'bg_' )
											{
												// $rvar .= $val . ', ';
												// echo '<pre>';
												// print_r(background_field_validate($rvar, $val, $key));
												// echo '</pre>';
												$rvar .= self::get_background_field_validated( $val, $key, 'repeater' );
											}
											else
											{
												$rvar .= $val . ', ';
											}
										}
									}
									if ( substr( $rvar, - 2 ) == ', ' )
									{
										$rvar = substr( $rvar, 0, - 2 );
									}
									$rvar .= ';' . chr( 13 );
									$scss_string_repeater .= $rvar;
								}

								if ( substr( $pvar, - 2 ) == ', ' )
								{
									$pvar = substr( $pvar, 0, - 2 );
									$pvar .= ');' . chr( 13 );
								}
								$scss_string_repeater .= $pvar;
							}
							// $scss_string .= chr(13);
							break;

						case 'text':
							$field_value = ( is_null( $finfo['value'] ) || $finfo['value'] == '' ) ? "null" : $finfo['value'];
							$scss_string .= "$" . $finfo['name'] . ": '$field_value';" . chr( 13 );
							break;

						case 'number' :
							$field_value = ( is_null( $finfo['value'] ) || $finfo['value'] == '' ) ? "null" : $finfo['value'];
							$scss_string .= "$" . $finfo['name'] . ": $field_value;" . chr( 13 );
							break;

						case 'background':
							$scss_string .= self::get_background_field_validated( $finfo['value'], $finfo['name'], 'background' );
							break;

						case 'true_false':
							$style_value = ( $finfo['value'] ) ? "true" : "false";
							$scss_string .= "$" . $finfo['name'] . ": $style_value;" . chr( 13 );

							break;

						case 'typography':
							$scss_string .= self::get_typography_field_validate( $finfo['value'], $finfo['name'] );
							break;

						default:
							if ( is_null( $finfo['value'] ) || $finfo['value'] == '' )
							{
								$scss_string .= '$' . $finfo['name'] . ': null;' . chr( 13 );
							}
							else
							{
								if ( is_array( $finfo['value'] ) )
								{
									$scss_string .= '$' . $finfo['name'] . ': ';

									foreach ( $finfo['value'] as $key => $value )
									{
										if ( is_null( $value ) ) $value = 'null';

										$scss_string .= '(' . $key . ', ' . $value . '), ';
									}
									$scss_string = rtrim( $scss_string, ', ' );

									$scss_string .= ';' . chr( 13 );
								}
								else
								{
									$scss_string .= '$' . $finfo['name'] . ': ' . $finfo['value'] . ';' . chr( 13 );
								}
							}
							break;
					endswitch;
				}
			}
		}

		$scss_string .= $scss_string_repeater;
		$varfilename = $this->scss_dir . "/" . $this->get_full_name($this->scss_var_name, $append_css_var_file);

		try
		{
			file_put_contents($varfilename, $scss_string);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}

		$this->compile($append_css_var_file);
	}

	private function get_full_name($name, $append)
	{
		return $name . ($append ? "-$append" : "") . ".scss";
	}
}
