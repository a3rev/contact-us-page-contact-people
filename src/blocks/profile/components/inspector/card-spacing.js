/**
 * Internal dependencies
 */
import map from 'lodash/map';

import { PaddingControl, SpacingAttributes, IconBox } from '@bit/a3revsoftware.blockpress.spacing';

const { __ } = wp.i18n;
const { Component } = wp.element;
const {
	PanelBody,
	BaseControl,
	RangeControl,
	__experimentalToggleGroupControl: ToggleGroupControl,
	__experimentalToggleGroupControlOption: ToggleGroupControlOption,
} = wp.components;

const fieldName = '';
const cardSpacingAttributes = SpacingAttributes( fieldName );

export { cardSpacingAttributes };

/**
 * Inspector controls
 */
export default class InspectorCardSpacing extends Component {
	render() {
		const { attributes, setAttributes } = this.props;

		const { width, widthUnit } = attributes;

		const widthUnitList = [ { key: 'px', name: __( 'px' ) }, { key: '%', name: __( '%' ) } ];

		return (
			<PanelBody
				className="a3-blockpress-inspect-panel contact-people-inspect-panel"
				title={ __( 'Card Style' ) }
				initialOpen={ false }
			>
			<ToggleGroupControl
				__next40pxDefaultSize
				__nextHasNoMarginBottom
				label={ __( 'Card Width Type' ) }
				value={ widthUnit }
				onChange={ ( value ) => setAttributes( { widthUnit: value } ) }
				isBlock
			>
				{ map( widthUnitList, ( { name, key } ) => (
					<ToggleGroupControlOption
						key={ key }
						value={ key }
						label={ name }
					/>
				) ) }
			</ToggleGroupControl>
			<RangeControl
				__next40pxDefaultSize
				label={ __( 'Card Width' ) }
					value={ width ? width : 300 }
					onChange={ value => setAttributes( { width: value } ) }
					min={ 'px' === widthUnit ? 300 : 10 }
					max={ 'px' === widthUnit ? 2000 : 100 }
					allowReset
				/>

				<BaseControl className="a3-blockpress-control-spacing">
					<IconBox />
					<PaddingControl { ...this.props } fieldName={ fieldName } />
				</BaseControl>
			</PanelBody>
		);
	}
}
