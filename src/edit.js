import ServerSideRender from '@wordpress/server-side-render';
import {
	FormTokenField,
	ToggleControl,
	PanelBody,
	RangeControl,
} from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

import './editor.scss';

import { useSelect } from '@wordpress/data';

export default ( { attributes, setAttributes } ) => {
	const blockProps = useBlockProps();

	const posts = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecords( 'postType', 'post', {
			per_page: 100,
			_fields: 'id,title',
		} );
	}, [] );

	const { selectedPosts } = attributes;

	let postNames = [];
	let postsFieldValue = [];
	if ( posts !== null ) {
		postNames = posts.map( ( post ) => post.title.raw );

		postsFieldValue = selectedPosts.map( ( postId ) => {
			const wantedPost = posts.find( ( post ) => {
				return post.id === postId;
			} );
			if ( wantedPost === undefined || ! wantedPost ) {
				return false;
			}
			return wantedPost.title.raw;
		} );
	}

	const onChange = ( postTokens ) => {
		// Build array of selected posts.
		const postTokensArray = [];
		postTokens.forEach( ( postName ) => {
			const matchingPost = posts.find( ( post ) => {
				return post.title.raw === postName;
			} );
			if ( matchingPost !== undefined ) {
				postTokensArray.push( matchingPost.id );
			}
		} );

		setAttributes( {
			selectedPosts: postTokensArray,
		} );
	};
	attributes.editing = true;

	return (
		<>
			<InspectorControls>
				<PanelBody title="CSS Only Carousel Settings">
					<FormTokenField
						label="Choose posts to display featured image in carousel"
						onChange={ onChange }
						suggestions={ postNames }
						value={ postsFieldValue }
					/>
					<ToggleControl
						label="Link to posts"
						help={
							attributes.linkThru
								? 'Images will link to posts.'
								: 'Images will not link to posts.'
						}
						checked={ attributes.linkThru }
						onChange={ () =>
							setAttributes( { linkThru: ! attributes.linkThru } )
						}
					/>
					<RangeControl
						label="Slide delay"
						value={ attributes.delay }
						onChange={ ( value ) =>
							setAttributes( { delay: value } )
						}
						min={ 2 }
						max={ 60 }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block="coderaaron/css-only-carousel"
					attributes={ attributes }
				/>
			</div>
		</>
	);
};
