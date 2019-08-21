const { __ } = wp.i18n;
const { Component } = wp.element;
const { PanelBody, ToggleControl, SelectControl } = wp.components;

/**
 * Inspector controls
 */
export default class InspectorGlobalSettings extends Component {
	render() {
		const { attributes, setAttributes } = this.props;

		const { contactID, align, alignWrap } = attributes;

		const contactProfileList = [
			{
				label: __( 'Select a Profile' ),
				value: '',
			},
			...JSON.parse( contact_people_vars.contactList ),
		];

		return (
			<PanelBody title={ __( 'Contact Profile' ) }>
				<SelectControl
					label={ __( 'Profile' ) }
					value={ contactID ? contactID : '' }
					onChange={ value => setAttributes( { contactID: value } ) }
					options={ contactProfileList }
				/>

				{ 'left' === align || 'right' === align ? (
					<ToggleControl
						label={ __( 'Align Wrap' ) }
						checked={ !! alignWrap }
						onChange={ () => setAttributes( { alignWrap: ! alignWrap } ) }
					/>
				) : null }
			</PanelBody>
		);
	}
}
