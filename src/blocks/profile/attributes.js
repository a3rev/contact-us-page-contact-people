const ProfileAttributes = {
	blockID: {
		type: 'string',
	},
	contactID: {
		type: 'string',
		default: '',
	},
	align: {
		type: 'string',
		default: 'none',
	},
	alignWrap: {
		type: 'boolean',
		default: false,
	},
	width: {
		type: 'number',
		default: 300,
	},
	widthUnit: {
		type: 'string',
		default: 'px',
	},
	/**
	 * For previewing?
	 */
	isPreview: {
		type: 'boolean',
		default: false,
	},
};

export default ProfileAttributes;
