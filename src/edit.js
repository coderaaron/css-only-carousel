import ServerSideRender from '@wordpress/server-side-render';
import { useEffect } from '@wordpress/element';
import {
	FormTokenField,
	ToggleControl,
	PanelBody,
	RangeControl,
	SelectControl,
} from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

import './editor.scss';

import { useSelect } from '@wordpress/data';

export default ( { clientId, attributes, setAttributes } ) => {
	const blockProps = useBlockProps();

	const posts = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecords( 'postType', 'post', {
			per_page: 100,
			_fields: 'id,title',
		} );
	}, [] );

	const { selectedPosts, id } = attributes;
	useEffect( () => {
		if ( 0 === id.length ) {
			setAttributes( {
				id: clientId,
			} );
		}
	}, [] );

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
					<SelectControl
						label="Transition type"
						value={ attributes.transition }
						options={ [
							{ label: 'Slide', value: 'slide' },
							{ label: 'Fade', value: 'fade' },
						] }
						onChange={ ( newTranisition ) =>
							setAttributes( {
								transition: newTranisition,
							} )
						}
					/>
					<ToggleControl
						label="Show navigation arrows"
						help={
							attributes.showArrows
								? 'Arrows will show.'
								: 'Arrows will not show.'
						}
						checked={ attributes.showArrows }
						onChange={ () =>
							setAttributes( {
								showArrows: ! attributes.showArrows,
							} )
						}
					/>
					<ToggleControl
						label="Auto play"
						help={
							attributes.autoPlay
								? 'Automatically advance to the next slide after a delay.'
								: 'Manually advance through the slides.'
						}
						checked={ attributes.autoPlay }
						onChange={ () =>
							setAttributes( {
								autoPlay: ! attributes.autoPlay,
							} )
						}
					/>
					{ attributes.autoPlay ? (
						<RangeControl
							label="Slide delay"
							help="In seconds"
							value={ attributes.delay }
							onChange={ ( value ) =>
								setAttributes( { delay: value } )
							}
							min={ 2 }
							max={ 60 }
						/>
					) : (
						''
					) }
					<SelectControl
						label="Navigation 'dot' type"
						value={ attributes.dotType }
						options={ [
							{ label: 'Dots', value: 'dots' },
							{ label: 'Thumbnails', value: 'thumbs' },
							{ label: 'None', value: 'none' },
						] }
						onChange={ ( newType ) =>
							setAttributes( {
								dotType: newType,
							} )
						}
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
