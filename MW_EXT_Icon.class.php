<?php

namespace MediaWiki\Extension\MW_EXT_Icon;

use OutputPage;
use Parser;
use PPFrame;
use Skin;

/**
 * Class MW_EXT_Icon
 * ------------------------------------------------------------------------------------------------------------------ */
class MW_EXT_Icon {

	/**
	 * Clear DATA (escape html).
	 *
	 * @param $string
	 *
	 * @return string
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function clearData( $string ) {
		$outString = htmlspecialchars( trim( $string ), ENT_QUOTES );

		return $outString;
	}

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'icon', __CLASS__ . '::onRenderTag', Parser::SFH_OBJECT_ARGS );

		return true;
	}

	/**
	 * Render tag function.
	 *
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @param array $args
	 *
	 * @return string
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onRenderTag( Parser $parser, PPFrame $frame, array $args ) {
		// Get options parser.
		$getOptions = self::extractOptions( $args, $frame );

		// Argument: name.
		$getName = self::clearData( $getOptions['name'] ?? '' ?: '' );
		$outName = $getName;

		// Argument: size.
		$getSize = self::clearData( $getOptions['size'] ?? '' ?: '' );
		$outSize = empty( $getSize ) ? '' : 'font-size:' . $getOptions['size'] . 'em;';

		// Argument: color.
		$getColor = self::clearData( $getOptions['color'] ?? '' ?: '' );
		$outColor = empty( $getColor ) ? '' : 'color:' . $getOptions['color'] . ';';

		// Argument: options.
		$getCustom = self::clearData( $getOptions['options'] ?? '' ?: '' );
		$outCustom = $getCustom;

		// Out HTML.
		$outHTML = '<span class="' . $outName . ' ' . $outCustom . ' mw-ext-fa" style="' . $outSize . $outColor . '"></span>';

		// Out parser.
		$outParser = $outHTML;

		return $outParser;
	}

	/**
	 * Converts an array of values in form [0] => "name=value" into a real
	 * associative array in form [name] => value. If no = is provided,
	 * true is assumed like this: [name] => true.
	 *
	 * @param array $options
	 * @param PPFrame $frame
	 *
	 * @return array
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function extractOptions( array $options, PPFrame $frame ) {
		$results = [];

		foreach ( $options as $option ) {
			$pair = explode( '=', $frame->expand( $option ), 2 );

			if ( count( $pair ) === 2 ) {
				$name             = self::clearData( $pair[0] );
				$value            = self::clearData( $pair[1] );
				$results[ $name ] = $value;
			}

			if ( count( $pair ) === 1 ) {
				$name             = self::clearData( $pair[0] );
				$results[ $name ] = true;
			}
		}

		return $results;
	}

	/**
	 * Load resource function.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 *
	 * @return bool
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.fa.styles' ] );

		return true;
	}
}
