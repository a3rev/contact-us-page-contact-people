/**
 * Internal dependencies
 */
import map from 'lodash/map';

import { PaddingControl, SpacingAttributes, IconBox } from '@bit/a3revsoftware.blockpress.spacing';

const { __ } = wp.i18n;
const { Component } = wp.element;
const { PanelBody, BaseControl, ButtonGroup, Button, RangeControl } = wp.components;

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
				<ButtonGroup className="a3-blockpress-size-type-options" aria-label={ __( 'Card Width Type' ) }>
					{ map( widthUnitList, ( { name, key } ) => (
						<Button
							key={ key }
							className="size-type-btn"
							isSmall
							isPrimary={ widthUnit === key }
							aria-pressed={ widthUnit === key }
							onClick={ () => setAttributes( { widthUnit: key } ) }
						>
							{ name }
						</Button>
					) ) }
				</ButtonGroup>
				<RangeControl
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
