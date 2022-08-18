(()=>{"use strict";var e,r={311:(e,r,o)=>{const t=window.wp.element,n=window.wp.blocks,l=window.wp.i18n,c=window.wp.editPost,s=window.wp.components,a=window.wp.coreData,i=window.wp.data,u=window.wp.plugins,d=window.wp.serverSideRender;var w=o.n(d);const p=window.wp.blockEditor;(0,n.registerBlockType)("coderaaron/css-only-carousel",{edit:function(e){const r=(0,p.useBlockProps)();return(0,t.createElement)("div",r,(0,t.createElement)(w(),{block:"coderaaron/css-only-carousel",attributes:e.attributes}))},icon:()=>(0,t.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 22.99 18.12",height:"24",width:"24"},(0,t.createElement)("path",{d:"M10.66,4.52l.11,.5-.66-.13-.08,.48-.78-.38,.4,.93-.72-.06-.27,.4c-.14-.24-.3-.46-.51-.64l-.3,.42-.42-.62c-1.74,.16-3.43,4.71-3.43,6.26,0,.13,.06,.14,.18,.14,.69,0,1.36-.56,1.84-1.01l-.03,.67,.29-.11v.43l.64-.32-.1,.82,.16-.08,.03,.51,.59-.05c-.13,.27-.22,.58-.35,.85l.26-.06-.11,.51,.56-.16-.32,.61c-.93,.77-2.05,1.46-3.3,1.46-.77,0-1.76-.22-2.35-.75C.43,14.6,0,13.24,0,11.75,0,8.76,1.34,5.78,3.15,3.44c.9-1.15,2.59-2.1,4.07-2.1,1.98,0,3.1,1.47,3.91,3.1l-.46,.06Zm-.46-.93c-.62-1.12-1.62-1.84-2.93-1.84s-2.96,.86-3.79,1.94C1.7,5.94,.4,8.85,.4,11.75c0,2.06,.99,3.23,3.14,3.23,1.23,0,2.32-.69,3.23-1.44l.24-.42-.53,.14,.11-.53-.34,.1,.18-.69-.9,.53,.27-.75-.45,.11,.06-.21c-.34,.29-.77,.54-1.22,.54-.86,0-1.09-1.01-1.09-1.7,0-1.36,1.06-3.7,1.82-4.82,.45-.66,.93-1.34,1.81-1.34,.53,0,.85,.37,1.1,.78l.24-.34,.62,.05-.43-.99,.88,.42,.08-.45,.61,.13-.1-.48,.43-.05Z"}),(0,t.createElement)("path",{d:"M16.04,3.19l.21,.42-.74,.35,.83,.4-.51,.32,.13,.54c-.22-.05-.45-.1-.67-.1-.99,0-1.47,.66-1.47,1.6,0,1.63,1.09,3.04,1.09,4.71,0,2.56-2.42,4.56-4.88,4.56-.83,0-1.68-.21-2.39-.69l-.05-.08c-.35-.13-.69-.29-.99-.5l-.53-.83,.78-.08-.13-.46,.34-.11-.26-.91,.93,.21-.27-.51,.54-.06-.34-.78c.51,.29,1.1,.48,1.7,.48,.91,0,1.62-.56,1.62-1.5,0-1.18-1.25-2.31-1.25-4.34,0-2.46,1.95-4.35,4.4-4.35,.69,0,1.36,.14,2,.4l-.51,.58,.08,.14c.24,.05,.46,.11,.69,.21l-.35,.4Zm-.59-1.17c-.42-.14-.85-.22-1.3-.22-1.2,0-2.39,.62-3.17,1.52-.61,.69-.85,1.62-.85,2.51,0,1.86,1.23,2.96,1.23,4.35,0,1.1-.72,2.1-1.89,2.1-.35,0-.69-.08-1.01-.22l.1,.22-.48,.06,.42,.77-1.14-.26,.18,.66-.34,.11,.14,.54-.59,.06,.13,.21c.69,.46,1.52,.67,2.34,.67,2.43,0,4.8-1.98,4.8-4.5,0-1.65-1.09-3.06-1.09-4.69,0-.99,.5-1.68,1.54-1.68,.19,0,.4,.03,.59,.06l-.13-.46,.45-.27-.9-.51,.85-.3-.19-.37,.3-.37Z"}),(0,t.createElement)("path",{d:"M22.64,3.19l.21,.42-.74,.35,.83,.4-.51,.32,.13,.54c-.22-.05-.45-.1-.67-.1-.99,0-1.47,.66-1.47,1.6,0,1.63,1.09,3.04,1.09,4.71,0,2.56-2.42,4.56-4.88,4.56-.83,0-1.68-.21-2.38-.69l-.05-.08c-.35-.13-.69-.29-.99-.5l-.53-.83,.78-.08-.13-.46,.34-.11-.26-.91,.93,.21-.27-.51,.54-.06-.34-.78c.51,.29,1.1,.48,1.7,.48,.91,0,1.62-.56,1.62-1.5,0-1.18-1.25-2.31-1.25-4.34,0-2.46,1.95-4.35,4.4-4.35,.69,0,1.36,.14,2,.4l-.51,.58,.08,.14c.24,.05,.46,.11,.69,.21l-.35,.4Zm-.59-1.17c-.42-.14-.85-.22-1.3-.22-1.2,0-2.38,.62-3.17,1.52-.61,.69-.85,1.62-.85,2.51,0,1.86,1.23,2.96,1.23,4.35,0,1.1-.72,2.1-1.89,2.1-.35,0-.69-.08-1.01-.22l.1,.22-.48,.06,.42,.77-1.14-.26,.18,.66-.34,.11,.14,.54-.59,.06,.13,.21c.69,.46,1.52,.67,2.34,.67,2.43,0,4.8-1.98,4.8-4.5,0-1.65-1.09-3.06-1.09-4.69,0-.99,.5-1.68,1.54-1.68,.19,0,.4,.03,.59,.06l-.13-.46,.45-.27-.9-.51,.85-.3-.19-.37,.3-.37Z"})),save:function(){return(0,t.createElement)("p",p.useBlockProps.save(),(0,l.__)("Css Only Carousel – hello from the saved content!","css-only-carousel"))}}),(0,u.registerPlugin)("css-only-carousel-metabox",{render:()=>{const e=(0,i.useSelect)((e=>e("core/editor").getCurrentPostType()),[]),[r,o]=(0,a.useEntityProp)("postType",e,"meta"),n="use_in_carousel";return(0,t.createElement)(c.PluginPostStatusInfo,{className:"css-only-carousel-checkbox"},(0,t.createElement)(s.CheckboxControl,{label:(0,l.__)("Use Featured Image in Carousel","css-only-carousel"),checked:(n,r.use_in_carousel||""),onChange:e=>((e,t)=>o({...r,use_in_carousel:t}))(0,e)}))}})}},o={};function t(e){var n=o[e];if(void 0!==n)return n.exports;var l=o[e]={exports:{}};return r[e](l,l.exports,t),l.exports}t.m=r,e=[],t.O=(r,o,n,l)=>{if(!o){var c=1/0;for(u=0;u<e.length;u++){o=e[u][0],n=e[u][1],l=e[u][2];for(var s=!0,a=0;a<o.length;a++)(!1&l||c>=l)&&Object.keys(t.O).every((e=>t.O[e](o[a])))?o.splice(a--,1):(s=!1,l<c&&(c=l));if(s){e.splice(u--,1);var i=n();void 0!==i&&(r=i)}}return r}l=l||0;for(var u=e.length;u>0&&e[u-1][2]>l;u--)e[u]=e[u-1];e[u]=[o,n,l]},t.n=e=>{var r=e&&e.__esModule?()=>e.default:()=>e;return t.d(r,{a:r}),r},t.d=(e,r)=>{for(var o in r)t.o(r,o)&&!t.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:r[o]})},t.o=(e,r)=>Object.prototype.hasOwnProperty.call(e,r),(()=>{var e={826:0,431:0};t.O.j=r=>0===e[r];var r=(r,o)=>{var n,l,c=o[0],s=o[1],a=o[2],i=0;if(c.some((r=>0!==e[r]))){for(n in s)t.o(s,n)&&(t.m[n]=s[n]);if(a)var u=a(t)}for(r&&r(o);i<c.length;i++)l=c[i],t.o(e,l)&&e[l]&&e[l][0](),e[l]=0;return t.O(u)},o=self.webpackChunkcss_only_carousel=self.webpackChunkcss_only_carousel||[];o.forEach(r.bind(null,0)),o.push=r.bind(null,o.push.bind(o))})();var n=t.O(void 0,[431],(()=>t(311)));n=t.O(n)})();