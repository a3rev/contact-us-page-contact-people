/**
 * Internal dependencies
 */

import Inspector, { cardSpacingAttributes } from './inspector';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { Placeholder } = wp.components;
const { BlockControls, BlockAlignmentToolbar } = wp.blockEditor;
const { serverSideRender: ServerSideRender } = wp;

export { cardSpacingAttributes };

export default class BlockEdit extends Component {
	render() {
		const { attributes, isSelected, setAttributes } = this.props;

		const { contactID, align } = attributes;

		return (
			<Fragment>
				<BlockControls>
					<BlockAlignmentToolbar
						value={ align }
						onChange={ value => setAttributes( { align: value } ) }
						controls={ [ 'left', 'center', 'right' ] }
					/>
				</BlockControls>
				{ isSelected && <Inspector { ...this.props } /> }
				{ '' !== contactID ? (
					<ServerSideRender block="contact-people/profile" attributes={ attributes } />
				) : (
					<Placeholder label={ __( 'Contact Profile' ) }>
						{ __( 'Please choose a contact profile' ) }
					</Placeholder>
				) }
			</Fragment>
		);
	}
}
