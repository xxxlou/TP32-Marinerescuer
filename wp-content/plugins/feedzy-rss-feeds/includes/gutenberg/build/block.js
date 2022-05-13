!function(e){var t={};function r(a){if(t[a])return t[a].exports;var n=t[a]={i:a,l:!1,exports:{}};return e[a].call(n.exports,n,n.exports,r),n.l=!0,n.exports}r.m=e,r.c=t,r.d=function(e,t,a){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(r.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)r.d(a,n,function(t){return e[t]}.bind(null,n));return a},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=5)}([function(e,t,r){"use strict";const a=r(1),n=r(2),o=r(3),s=r(4);function i(e){if("string"!=typeof e||1!==e.length)throw new TypeError("arrayFormatSeparator must be single character string")}function l(e,t){return t.encode?t.strict?a(e):encodeURIComponent(e):e}function u(e,t){return t.decode?n(e):e}function p(e){const t=e.indexOf("#");return-1!==t&&(e=e.slice(0,t)),e}function c(e){const t=(e=p(e)).indexOf("?");return-1===t?"":e.slice(t+1)}function m(e,t){return t.parseNumbers&&!Number.isNaN(Number(e))&&"string"==typeof e&&""!==e.trim()?e=Number(e):!t.parseBooleans||null===e||"true"!==e.toLowerCase()&&"false"!==e.toLowerCase()||(e="true"===e.toLowerCase()),e}function d(e,t){i((t=Object.assign({decode:!0,sort:!0,arrayFormat:"none",arrayFormatSeparator:",",parseNumbers:!1,parseBooleans:!1},t)).arrayFormatSeparator);const r=function(e){let t;switch(e.arrayFormat){case"index":return(e,r,a)=>{t=/\[(\d*)\]$/.exec(e),e=e.replace(/\[\d*\]$/,""),t?(void 0===a[e]&&(a[e]={}),a[e][t[1]]=r):a[e]=r};case"bracket":return(e,r,a)=>{t=/(\[\])$/.exec(e),e=e.replace(/\[\]$/,""),t?void 0!==a[e]?a[e]=[].concat(a[e],r):a[e]=[r]:a[e]=r};case"comma":case"separator":return(t,r,a)=>{const n="string"==typeof r&&r.includes(e.arrayFormatSeparator),o="string"==typeof r&&!n&&u(r,e).includes(e.arrayFormatSeparator);r=o?u(r,e):r;const s=n||o?r.split(e.arrayFormatSeparator).map(t=>u(t,e)):null===r?r:u(r,e);a[t]=s};case"bracket-separator":return(t,r,a)=>{const n=/(\[\])$/.test(t);if(t=t.replace(/\[\]$/,""),!n)return void(a[t]=r?u(r,e):r);const o=null===r?[]:r.split(e.arrayFormatSeparator).map(t=>u(t,e));void 0!==a[t]?a[t]=[].concat(a[t],o):a[t]=o};default:return(e,t,r)=>{void 0!==r[e]?r[e]=[].concat(r[e],t):r[e]=t}}}(t),a=Object.create(null);if("string"!=typeof e)return a;if(!(e=e.trim().replace(/^[?#&]/,"")))return a;for(const n of e.split("&")){if(""===n)continue;let[e,s]=o(t.decode?n.replace(/\+/g," "):n,"=");s=void 0===s?null:["comma","separator","bracket-separator"].includes(t.arrayFormat)?s:u(s,t),r(u(e,t),s,a)}for(const e of Object.keys(a)){const r=a[e];if("object"==typeof r&&null!==r)for(const e of Object.keys(r))r[e]=m(r[e],t);else a[e]=m(r,t)}return!1===t.sort?a:(!0===t.sort?Object.keys(a).sort():Object.keys(a).sort(t.sort)).reduce((e,t)=>{const r=a[t];return Boolean(r)&&"object"==typeof r&&!Array.isArray(r)?e[t]=function e(t){return Array.isArray(t)?t.sort():"object"==typeof t?e(Object.keys(t)).sort((e,t)=>Number(e)-Number(t)).map(e=>t[e]):t}(r):e[t]=r,e},Object.create(null))}t.extract=c,t.parse=d,t.stringify=(e,t)=>{if(!e)return"";i((t=Object.assign({encode:!0,strict:!0,arrayFormat:"none",arrayFormatSeparator:","},t)).arrayFormatSeparator);const r=r=>t.skipNull&&null==e[r]||t.skipEmptyString&&""===e[r],a=function(e){switch(e.arrayFormat){case"index":return t=>(r,a)=>{const n=r.length;return void 0===a||e.skipNull&&null===a||e.skipEmptyString&&""===a?r:null===a?[...r,[l(t,e),"[",n,"]"].join("")]:[...r,[l(t,e),"[",l(n,e),"]=",l(a,e)].join("")]};case"bracket":return t=>(r,a)=>void 0===a||e.skipNull&&null===a||e.skipEmptyString&&""===a?r:null===a?[...r,[l(t,e),"[]"].join("")]:[...r,[l(t,e),"[]=",l(a,e)].join("")];case"comma":case"separator":case"bracket-separator":{const t="bracket-separator"===e.arrayFormat?"[]=":"=";return r=>(a,n)=>void 0===n||e.skipNull&&null===n||e.skipEmptyString&&""===n?a:(n=null===n?"":n,0===a.length?[[l(r,e),t,l(n,e)].join("")]:[[a,l(n,e)].join(e.arrayFormatSeparator)])}default:return t=>(r,a)=>void 0===a||e.skipNull&&null===a||e.skipEmptyString&&""===a?r:null===a?[...r,l(t,e)]:[...r,[l(t,e),"=",l(a,e)].join("")]}}(t),n={};for(const t of Object.keys(e))r(t)||(n[t]=e[t]);const o=Object.keys(n);return!1!==t.sort&&o.sort(t.sort),o.map(r=>{const n=e[r];return void 0===n?"":null===n?l(r,t):Array.isArray(n)?0===n.length&&"bracket-separator"===t.arrayFormat?l(r,t)+"[]":n.reduce(a(r),[]).join("&"):l(r,t)+"="+l(n,t)}).filter(e=>e.length>0).join("&")},t.parseUrl=(e,t)=>{t=Object.assign({decode:!0},t);const[r,a]=o(e,"#");return Object.assign({url:r.split("?")[0]||"",query:d(c(e),t)},t&&t.parseFragmentIdentifier&&a?{fragmentIdentifier:u(a,t)}:{})},t.stringifyUrl=(e,r)=>{r=Object.assign({encode:!0,strict:!0},r);const a=p(e.url).split("?")[0]||"",n=t.extract(e.url),o=t.parse(n,{sort:!1}),s=Object.assign(o,e.query);let i=t.stringify(s,r);i&&(i="?"+i);let u=function(e){let t="";const r=e.indexOf("#");return-1!==r&&(t=e.slice(r)),t}(e.url);return e.fragmentIdentifier&&(u="#"+l(e.fragmentIdentifier,r)),`${a}${i}${u}`},t.pick=(e,r,a)=>{a=Object.assign({parseFragmentIdentifier:!0},a);const{url:n,query:o,fragmentIdentifier:i}=t.parseUrl(e,a);return t.stringifyUrl({url:n,query:s(o,r),fragmentIdentifier:i},a)},t.exclude=(e,r,a)=>{const n=Array.isArray(r)?e=>!r.includes(e):(e,t)=>!r(e,t);return t.pick(e,n,a)}},function(e,t,r){"use strict";e.exports=e=>encodeURIComponent(e).replace(/[!'()*]/g,e=>"%"+e.charCodeAt(0).toString(16).toUpperCase())},function(e,t,r){"use strict";var a=new RegExp("%[a-f0-9]{2}","gi"),n=new RegExp("(%[a-f0-9]{2})+","gi");function o(e,t){try{return decodeURIComponent(e.join(""))}catch(e){}if(1===e.length)return e;t=t||1;var r=e.slice(0,t),a=e.slice(t);return Array.prototype.concat.call([],o(r),o(a))}function s(e){try{return decodeURIComponent(e)}catch(n){for(var t=e.match(a),r=1;r<t.length;r++)t=(e=o(t,r).join("")).match(a);return e}}e.exports=function(e){if("string"!=typeof e)throw new TypeError("Expected `encodedURI` to be of type `string`, got `"+typeof e+"`");try{return e=e.replace(/\+/g," "),decodeURIComponent(e)}catch(t){return function(e){for(var t={"%FE%FF":"��","%FF%FE":"��"},r=n.exec(e);r;){try{t[r[0]]=decodeURIComponent(r[0])}catch(e){var a=s(r[0]);a!==r[0]&&(t[r[0]]=a)}r=n.exec(e)}t["%C2"]="�";for(var o=Object.keys(t),i=0;i<o.length;i++){var l=o[i];e=e.replace(new RegExp(l,"g"),t[l])}return e}(e)}}},function(e,t,r){"use strict";e.exports=(e,t)=>{if("string"!=typeof e||"string"!=typeof t)throw new TypeError("Expected the arguments to be of type `string`");if(""===t)return[e];const r=e.indexOf(t);return-1===r?[e]:[e.slice(0,r),e.slice(r+t.length)]}},function(e,t,r){"use strict";e.exports=function(e,t){for(var r={},a=Object.keys(e),n=Array.isArray(t),o=0;o<a.length;o++){var s=a[o],i=e[s];(n?-1!==t.indexOf(s):t(s,i,e))&&(r[s]=i)}return r}},function(e,t,r){"use strict";r.r(t);var a={feeds:{type:"string"},max:{type:"number",default:5},offset:{type:"number",default:0},feed_title:{type:"boolean",default:!0},refresh:{type:"string",default:"12_hours"},sort:{type:"string",default:"default"},target:{type:"string",default:"_blank"},title:{type:"number"},meta:{type:"boolean",default:!0},lazy:{type:"boolean",default:!1},metafields:{type:"string",default:""},multiple_meta:{type:"string",default:""},summary:{type:"boolean",default:!0},summarylength:{type:"number"},keywords_title:{type:"string"},keywords_inc_on:{type:"string",default:"title"},keywords_ban:{type:"string"},keywords_exc_on:{type:"string",default:"title"},thumb:{type:"string",default:"auto"},default:{type:"object"},size:{type:"number",default:150},http:{type:"string"},referral_url:{type:"string"},columns:{type:"number",default:1},template:{type:"string",default:"default"},price:{type:"boolean",default:!0},route:{type:"string",default:"home"},feedData:{type:"object"},categories:{type:"object"},from_datetime:{type:"string"},to_datetime:{type:"string"}},n=r(0),o=r.n(n),s=lodash.isEmpty,i=wp.components.BaseControl;var l=(0,wp.compose.withInstanceId)((function(e){var t=e.label,r=e.selected,a=e.help,n=e.instanceId,o=e.onChange,l=e.disabled,u=e.options,p=void 0===u?[]:u,c="inspector-radio-image-control-".concat(n),m=function(e){return o(e.target.value)};return!s(p)&&wp.element.createElement(i,{label:t,id:c,help:a,className:"components-radio-image-control"},wp.element.createElement("div",{className:"components-radio-image-control__container"},p.map((function(e,t){return wp.element.createElement("div",{key:"".concat(c,"-").concat(t),className:"components-radio-image-control__option"},wp.element.createElement("input",{id:"".concat(c,"-").concat(t),className:"components-radio-image-control__input",type:"radio",name:c,value:e.value,onChange:m,checked:e.value===r,"aria-describedby":a?"".concat(c,"__help"):void 0,disabled:l}),wp.element.createElement("label",{htmlFor:"".concat(c,"-").concat(t),title:e.label},wp.element.createElement("img",{src:e.src}),wp.element.createElement("span",{class:"image-clickable"})))}))))}));function u(e){return(u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function p(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function c(e,t){for(var r=0;r<t.length;r++){var a=t[r];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}function m(e,t){return(m=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function d(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,a=h(e);if(t){var n=h(this).constructor;r=Reflect.construct(a,arguments,n)}else r=a.apply(this,arguments);return f(this,r)}}function f(e,t){return!t||"object"!==u(t)&&"function"!=typeof t?function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e):t}function h(e){return(h=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var y=wp.i18n.__,b=wp.hooks.applyFilters,g=wp.blockEditor||wp.editor,v=g.InspectorControls,w=g.MediaUpload,k=wp.element,E=k.Component,T=(k.Fragment,wp.components),C=T.BaseControl,x=T.ExternalLink,z=T.PanelBody,O=T.RangeControl,S=T.TextControl,j=T.Button,N=T.ToggleControl,F=T.SelectControl,R=T.ResponsiveWrapper,A=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&m(e,t)}(o,e);var t,r,a,n=d(o);function o(){return p(this,o),n.apply(this,arguments)}return t=o,(r=[{key:"render",value:function(){var e=this,t="",r=b("feedzy_widget_refresh_feed",[{label:y("1 Hour"),value:"1_hours"},{label:y("2 Hours"),value:"3_hours"},{label:y("12 Hours"),value:"12_hours"},{label:y("1 Day"),value:"1_days"},{label:y("3 Days"),value:"3_days"},{label:y("15 Days"),value:"15_days"}]);return"https"===this.props.attributes.http&&(t+=y("Please verify that the images exist on HTTPS.")),wp.element.createElement(v,{key:"inspector"},0!==this.props.attributes.status&&wp.element.createElement(z,null,wp.element.createElement(S,{label:y("Feed Source"),className:"feedzy-source",value:this.props.attributes.feeds,onChange:this.props.edit.onChangeFeed}),wp.element.createElement(j,{isLarge:!0,isPrimary:!0,type:"submit",onClick:this.props.edit.loadFeed,className:"loadFeed"},y("Load Feed"))),"fetched"===this.props.state.route&&[wp.element.createElement(z,{title:y("Feed Settings"),initialOpen:!0,className:"feedzy-options"},wp.element.createElement(O,{label:y("Number of Items"),value:Number(this.props.attributes.max)||5,onChange:this.props.edit.onChangeMax,min:1,max:this.props.attributes.feedData.items.length||10,beforeIcon:"sort",className:"feedzy-max"}),wp.element.createElement(O,{label:y("Ignore first N items"),value:Number(this.props.attributes.offset)||0,onChange:this.props.edit.onChangeOffset,min:0,max:this.props.attributes.feedData.items.length,beforeIcon:"sort",className:"feedzy-offset"}),null!==this.props.attributes.feedData.channel&&wp.element.createElement(N,{label:y("Display feed title?"),checked:!!this.props.attributes.feed_title,onChange:this.props.edit.onToggleFeedTitle,className:"feedzy-title"}),wp.element.createElement(N,{label:y("Lazy load feed?"),checked:!!this.props.attributes.lazy,onChange:this.props.edit.onToggleLazy,className:"feedzy-lazy",help:y("Only on the front end.")}),wp.element.createElement(F,{label:y("Feed Caching Time"),value:this.props.attributes.refresh,options:r,onChange:this.props.edit.onRefresh,className:"feedzy-refresh"}),wp.element.createElement(F,{label:y("Sorting Order"),value:this.props.attributes.sort,options:[{label:y("Default"),value:"default"},{label:y("Date Descending"),value:"date_desc"},{label:y("Date Ascending"),value:"date_asc"},{label:y("Title Descending"),value:"title_desc"},{label:y("Title Ascending"),value:"title_asc"}],onChange:this.props.edit.onSort,className:"feedzy-sort"})),wp.element.createElement(z,{title:y("Item Options"),initialOpen:!1,className:"feedzy-item-options"},wp.element.createElement(F,{label:y("Open Links In"),value:this.props.attributes.target,options:[{label:y("New Tab"),value:"_blank"},{label:y("Same Tab"),value:"_self"}],onChange:this.props.edit.onTarget}),wp.element.createElement(S,{label:y("Title Character Limit"),help:y("Leave empty to show full title. A value of 0 will remove the title."),type:"number",value:this.props.attributes.title,onChange:this.props.edit.onTitle,className:"feedzy-title-length"}),wp.element.createElement(C,null,wp.element.createElement(S,{label:feedzyjs.isPro?y("Should we display additional meta fields out of author, date, time or categories? (comma-separated list, in order of display)."):y("Should we display additional meta fields out of author, date or time? (comma-separated list, in order of display)."),help:y('Leave empty to display all and "no" to display nothing.'),placeholder:feedzyjs.isPro?y("(eg: author, date, time, tz=local, categories)"):y("(eg: author, date, time, tz=local)"),value:this.props.attributes.metafields,onChange:this.props.edit.onChangeMeta,className:"feedzy-meta"}),wp.element.createElement(S,{label:y("When using multiple sources, should we display additional meta fields? - source (comma-separated list)."),placeholder:y("(eg: source)"),value:this.props.attributes.multiple_meta,onChange:this.props.edit.onChangeMultipleMeta,className:"feedzy-multiple-meta"}),wp.element.createElement(x,{href:"https://docs.themeisle.com/article/1089-how-to-display-author-date-or-time-from-the-feed"},y("You can find more info about available meta field values here."))),wp.element.createElement(N,{label:y("Display post description?"),checked:!!this.props.attributes.summary,onChange:this.props.edit.onToggleSummary,className:"feedzy-summary"}),this.props.attributes.summary&&wp.element.createElement(S,{label:y("Description Character Limit"),help:y("Leave empty to show full description."),type:"number",value:this.props.attributes.summarylength,onChange:this.props.edit.onSummaryLength,className:"feedzy-summary-length",min:0}),feedzyjs.isPro&&[wp.element.createElement(S,{label:y("Only display if selected field contains:"),help:y("Use comma(,) and plus(+) keyword"),value:this.props.attributes.keywords_title,onChange:this.props.edit.onKeywordsTitle,className:"feedzy-include"}),wp.element.createElement(F,{label:y("Select a field if you want to inc keyword."),value:this.props.attributes.keywords_inc_on,options:[{label:y("Title"),value:"title"},{label:y("Author"),value:"author"},{label:y("Description"),value:"description"}],onChange:this.props.edit.onKeywordsIncludeOn}),wp.element.createElement(S,{label:y("Exclude if selected field contains:"),help:y("Use comma(,) and plus(+) keyword"),value:this.props.attributes.keywords_ban,onChange:this.props.edit.onKeywordsBan,className:"feedzy-ban"}),wp.element.createElement(F,{label:y("Select a field if you want to exc keyword."),value:this.props.attributes.keywords_exc_on,options:[{label:y("Title"),value:"title"},{label:y("Author"),value:"author"},{label:y("Description"),value:"description"}],onChange:this.props.edit.onKeywordsExcludeOn}),wp.element.createElement("p",null,y("Filter feed item by date range.")),wp.element.createElement(S,{type:"datetime-local",label:y("From:"),value:this.props.attributes.from_datetime,onChange:this.props.edit.onFromDateTime}),wp.element.createElement(S,{type:"datetime-local",label:y("To:"),value:this.props.attributes.to_datetime,onChange:this.props.edit.onToDateTime})]),wp.element.createElement(z,{title:y("Item Image Options"),initialOpen:!1,className:"feedzy-image-options"},wp.element.createElement(F,{label:y("Display first image if available?"),value:this.props.attributes.thumb,options:[{label:y("Yes (without  a fallback image)"),value:"auto"},{label:y("Yes (with a fallback image)"),value:"yes"},{label:y("No"),value:"no"}],onChange:this.props.edit.onThumb,className:"feedzy-thumb"}),"no"!==this.props.attributes.thumb&&["auto"!==this.props.attributes.thumb&&wp.element.createElement("div",{className:"feedzy-blocks-base-control"},wp.element.createElement("label",{className:"blocks-base-control__label",for:"inspector-media-upload"},y("Fallback image if no image is found.")),wp.element.createElement(w,{type:"image",id:"inspector-media-upload",value:this.props.attributes.default,onSelect:this.props.edit.onDefault,render:function(t){var r=t.open;return[void 0!==e.props.attributes.default&&[wp.element.createElement(R,{naturalWidth:e.props.attributes.default.width,naturalHeight:e.props.attributes.default.height},wp.element.createElement("img",{src:e.props.attributes.default.url,alt:y("Featured image")})),wp.element.createElement(j,{isLarge:!0,isSecondary:!0,onClick:function(){return e.props.setAttributes({default:void 0})},style:{marginTop:"10px"}},y("Remove Image"))],wp.element.createElement(j,{isLarge:!0,isPrimary:!0,onClick:r,style:{marginTop:"10px"},className:void 0===e.props.attributes.default&&"feedzy_image_upload"},y("Upload Image"))]}})),wp.element.createElement(S,{label:y("Thumbnails dimension."),type:"number",value:this.props.attributes.size,onChange:this.props.edit.onSize}),wp.element.createElement(F,{label:y("How should we treat HTTP images?"),value:this.props.attributes.http,options:[{label:y("Show with HTTP link"),value:"auto"},{label:y("Force HTTPS"),value:"https"},{label:y("Ignore and show the default image instead"),value:"default"}],onChange:this.props.edit.onHTTP,className:"feedzy-http",help:t})]),feedzyjs.isPro&&wp.element.createElement(z,{title:y("Pro Features"),initialOpen:!1,className:"feedzy-pro-options"},wp.element.createElement(N,{label:y("Display price if available?"),help:this.props.attributes.price&&"default"===this.props.attributes.template?y("Choose a different template for this to work."):null,checked:!!this.props.attributes.price,onChange:this.props.edit.onTogglePrice,className:"feedzy-pro-price"}),wp.element.createElement(S,{label:y("Referral URL parameters."),help:y('Without ("?")'),placeholder:_("(eg. promo_code=feedzy_is_awesome)"),value:this.props.attributes.referral_url,onChange:this.props.edit.onReferralURL}),wp.element.createElement(O,{label:y("Columns"),help:y("How many columns we should use to display the feed items?"),value:this.props.attributes.columns||1,onChange:this.props.edit.onColumns,min:1,max:6,beforeIcon:"sort",allowReset:!0}),wp.element.createElement(l,{label:y("Feed Template"),selected:this.props.attributes.template,options:[{label:y("Default"),src:feedzyjs.imagepath+"feedzy-default-template.jpg",value:"default"},{label:y("Style 1"),src:feedzyjs.imagepath+"feedzy-style1-template.jpg",value:"style1"},{label:y("Style 2"),src:feedzyjs.imagepath+"feedzy-style2-template.jpg",value:"style2"}],onChange:this.props.edit.onTemplate,className:"feedzy-pro-template"}))])}}])&&c(t.prototype,r),a&&c(t,a),o}(E),L=function(e){var t=document.createElement("div");return t.innerHTML=e,void 0!==t.innerText?t.innerText:t.textContent},P=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t="",r=[];return""!==e&&e.replace(/[^a-zA-Z]/g,"").length<=500&&(e.split(",").forEach((function(e){""!==(e=e.trim())&&(e=e.split("+").map((function(e){return"(?=.*"+(e=e.trim())+")"})),r.push(e.join("")))})),t="^"+(t=r.join("|"))+".*$",t=new RegExp(t,"i")),t};function D(e){return(D="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function I(){return(I=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var a in r)Object.prototype.hasOwnProperty.call(r,a)&&(e[a]=r[a])}return e}).apply(this,arguments)}function U(e,t,r,a,n,o,s){try{var i=e[o](s),l=i.value}catch(e){return void r(e)}i.done?t(l):Promise.resolve(l).then(a,n)}function M(e){return function(){var t=this,r=arguments;return new Promise((function(a,n){var o=e.apply(t,r);function s(e){U(o,a,n,s,i,"next",e)}function i(e){U(o,a,n,s,i,"throw",e)}s(void 0)}))}}function B(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function K(e,t){for(var r=0;r<t.length;r++){var a=t[r];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}function H(e,t){return(H=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function $(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,a=q(e);if(t){var n=q(this).constructor;r=Reflect.construct(a,arguments,n)}else r=a.apply(this,arguments);return Y(this,r)}}function Y(e,t){return!t||"object"!==D(t)&&"function"!=typeof t?V(e):t}function V(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function q(e){return(q=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var W=wp.i18n.__,X=wp,Q=(X.apiFetch,X.apiRequest),Z=wp.element,G=Z.Component,J=(Z.Fragment,wp.components),ee=J.ExternalLink,te=J.Placeholder,re=J.TextControl,ae=J.Button,ne=J.Spinner,oe=(wp.date.date,function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&H(e,t)}(l,e);var t,r,a,n,s,i=$(l);function l(){var e;return B(this,l),(e=i.apply(this,arguments)).loadFeed=e.loadFeed.bind(V(e)),e.loadCategories=e.loadCategories.bind(V(e)),e.metaExists=e.metaExists.bind(V(e)),e.multipleMetaExists=e.multipleMetaExists.bind(V(e)),e.getImageURL=e.getImageURL.bind(V(e)),e.getValidateURL=e.getValidateURL.bind(V(e)),e.onChangeFeed=e.onChangeFeed.bind(V(e)),e.onChangeMax=e.onChangeMax.bind(V(e)),e.onChangeOffset=e.onChangeOffset.bind(V(e)),e.onToggleFeedTitle=e.onToggleFeedTitle.bind(V(e)),e.onRefresh=e.onRefresh.bind(V(e)),e.onSort=e.onSort.bind(V(e)),e.onTarget=e.onTarget.bind(V(e)),e.onTitle=e.onTitle.bind(V(e)),e.onChangeMeta=e.onChangeMeta.bind(V(e)),e.onChangeMultipleMeta=e.onChangeMultipleMeta.bind(V(e)),e.onToggleSummary=e.onToggleSummary.bind(V(e)),e.onToggleLazy=e.onToggleLazy.bind(V(e)),e.onSummaryLength=e.onSummaryLength.bind(V(e)),e.onKeywordsTitle=e.onKeywordsTitle.bind(V(e)),e.onKeywordsBan=e.onKeywordsBan.bind(V(e)),e.onThumb=e.onThumb.bind(V(e)),e.onDefault=e.onDefault.bind(V(e)),e.onSize=e.onSize.bind(V(e)),e.onHTTP=e.onHTTP.bind(V(e)),e.onReferralURL=e.onReferralURL.bind(V(e)),e.onColumns=e.onColumns.bind(V(e)),e.onTemplate=e.onTemplate.bind(V(e)),e.onTogglePrice=e.onTogglePrice.bind(V(e)),e.onKeywordsIncludeOn=e.onKeywordsIncludeOn.bind(V(e)),e.onKeywordsExcludeOn=e.onKeywordsExcludeOn.bind(V(e)),e.onFromDateTime=e.onFromDateTime.bind(V(e)),e.onToDateTime=e.onToDateTime.bind(V(e)),e.feedzyCategoriesList=e.feedzyCategoriesList.bind(V(e)),e.state={route:e.props.attributes.route,loading:!1,error:!1},e}return t=l,(r=[{key:"componentDidMount",value:(s=M(regeneratorRuntime.mark((function e(){var t=this;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:this.loadFeed(),void 0===this.props.attributes.categories&&(this.props.attributes.meta||this.props.setAttributes({meta:!0,metafields:"no"}),setTimeout((function(){t.loadCategories()})));case 2:case"end":return e.stop()}}),e,this)}))),function(){return s.apply(this,arguments)})},{key:"componentDidUpdate",value:(n=M(regeneratorRuntime.mark((function e(t){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:"reload"===this.state.route&&this.loadFeed();case 1:case"end":return e.stop()}}),e,this)}))),function(e){return n.apply(this,arguments)})},{key:"loadFeed",value:function(){var e=this,t=this.props.attributes.feeds;if(void 0!==t){if(function(e,t){if(void 0===t)return!1;for(var r=!1,a=0;a<t.length;a++)if(t[a]===e){r=!0;break}return r}(t,this.props.attributes.categories)){var r=t;t=o.a.stringify({category:r},{arrayFormat:"bracket"})}else t=t.replace(/\s/g,"").split(",").filter((function(e){return""!==e})),t=o.a.stringify({url:t},{arrayFormat:"bracket"});this.setState({route:"home",loading:!0}),Q({path:"/feedzy/v1/feed?".concat(t),method:"POST",data:this.props.attributes}).then((function(t){return e.unmounting?t:t.error?(e.setState({route:"home",loading:!1,error:!0}),t):(e.props.setAttributes({feedData:t}),e.setState({route:"fetched",loading:!1}),t)})).fail((function(t){return e.setState({route:"home",loading:!1,error:!0}),t}))}}},{key:"loadCategories",value:function(){var e=this;Q({path:"/wp/v2/feedzy_categories?per_page=100"}).then((function(t){if(e.unmounting)return t;var r=0,a=[];t.forEach((function(e){a[r]=e.slug,r+=1}));var n=e;n.props.setAttributes({categories:a}),jQuery(".feedzy-source input").autocomplete({classes:{"ui-autocomplete":"feedzy-ui-autocomplete"},source:a,minLength:0,select:function(e,t){n.props.setAttributes({feeds:t.item.label})}})})).fail((function(e){return e}))}},{key:"metaExists",value:function(e){return 0<=this.props.attributes.metafields.replace(/\s/g,"").split(",").indexOf(e)||""===this.props.attributes.metafields}},{key:"multipleMetaExists",value:function(e){return 0<=this.props.attributes.multiple_meta.replace(/\s/g,"").split(",").indexOf(e)||""===this.props.attributes.multiple_meta}},{key:"getImageURL",value:function(e,t){var r=e.thumbnail?e.thumbnail:this.props.attributes.default?this.props.attributes.default.url:feedzyjs.imagepath+"feedzy.svg";switch(this.props.attributes.http){case"default":-1===r.indexOf("https")&&0===r.indexOf("http")&&(r=this.props.attributes.default?this.props.attributes.default.url:feedzyjs.imagepath+"feedzy.svg");break;case"https":r=r.replace(/http:/g,"https:")}return t&&(r="url("+r+")"),r}},{key:"onChangeFeed",value:function(e){this.props.setAttributes({feeds:e})}},{key:"onChangeMax",value:function(e){this.props.setAttributes({max:e?Number(e):5})}},{key:"onChangeOffset",value:function(e){this.props.setAttributes({offset:Number(e)})}},{key:"onToggleFeedTitle",value:function(e){this.props.setAttributes({feed_title:!this.props.attributes.feed_title})}},{key:"onRefresh",value:function(e){this.props.setAttributes({refresh:e})}},{key:"onSort",value:function(e){this.props.setAttributes({sort:e})}},{key:"onTarget",value:function(e){this.props.setAttributes({target:e})}},{key:"onTitle",value:function(e){""!==e&&(e=Number(e))<0&&(e=0),this.props.setAttributes({title:e})}},{key:"onChangeMeta",value:function(e){this.props.setAttributes({metafields:e})}},{key:"onChangeMultipleMeta",value:function(e){this.props.setAttributes({multiple_meta:e})}},{key:"onToggleSummary",value:function(e){this.props.setAttributes({summary:!this.props.attributes.summary})}},{key:"onToggleLazy",value:function(e){this.props.setAttributes({lazy:!this.props.attributes.lazy})}},{key:"onSummaryLength",value:function(e){this.props.setAttributes({summarylength:Number(e)})}},{key:"onKeywordsTitle",value:function(e){this.props.setAttributes({keywords_title:e})}},{key:"onKeywordsBan",value:function(e){this.props.setAttributes({keywords_ban:e})}},{key:"onThumb",value:function(e){this.props.setAttributes({thumb:e})}},{key:"onDefault",value:function(e){this.props.setAttributes({default:e}),this.setState({route:"reload"})}},{key:"onSize",value:function(e){this.props.setAttributes({size:e?Number(e):150})}},{key:"onHTTP",value:function(e){this.props.setAttributes({http:e}),this.setState({route:"reload"})}},{key:"onReferralURL",value:function(e){this.props.setAttributes({referral_url:e})}},{key:"onColumns",value:function(e){this.props.setAttributes({columns:e})}},{key:"onTemplate",value:function(e){this.props.setAttributes({template:e})}},{key:"onTogglePrice",value:function(e){this.props.setAttributes({price:!this.props.attributes.price})}},{key:"onKeywordsIncludeOn",value:function(e){this.props.setAttributes({keywords_inc_on:e})}},{key:"onKeywordsExcludeOn",value:function(e){this.props.setAttributes({keywords_exc_on:e})}},{key:"onFromDateTime",value:function(e){this.props.setAttributes({from_datetime:e})}},{key:"onToDateTime",value:function(e){this.props.setAttributes({to_datetime:e})}},{key:"feedzyCategoriesList",value:function(e){jQuery(".feedzy-source input").autocomplete("search","")}},{key:"getValidateURL",value:function(){var e="https://validator.w3.org/feed/";return this.props.attributes.feeds&&(e+="check.cgi?url="+this.props.attributes.feeds),e}},{key:"render",value:function(){var e,t,r,a,n,o,s,i,l,u,p=this;return["fetched"===this.state.route&&wp.element.createElement(A,I({edit:this,state:this.state},this.props)),"home"===this.state.route&&wp.element.createElement("div",{className:this.props.className},wp.element.createElement(te,{key:"placeholder",icon:"rss",label:W("Feedzy RSS Feeds")},this.state.loading?wp.element.createElement("div",{key:"loading",className:"wp-block-embed is-loading"},wp.element.createElement(ne,null),wp.element.createElement("p",null,W("Fetching..."))):[wp.element.createElement("div",{className:"feedzy-source-wrap"},wp.element.createElement(re,{type:"url",className:"feedzy-source",placeholder:W("Enter URL or category of your feed here..."),onChange:this.onChangeFeed,value:this.props.attributes.feeds}),wp.element.createElement("span",{className:"dashicons dashicons-arrow-down-alt2",onClick:this.feedzyCategoriesList})),wp.element.createElement(ae,{isLarge:!0,isPrimary:!0,type:"submit",onClick:this.loadFeed},W("Load Feed")),wp.element.createElement(ee,{href:this.getValidateURL(),title:W("Validate Feed ")},W("Validate ")),this.state.error&&wp.element.createElement("div",null,W("Feed URL is invalid. Invalid feeds will NOT display items.")),wp.element.createElement("p",null,W("Enter the full URL of the feed source you wish to display here, or the name of a category you've created. Also you can add multiple URLs just separate them with a comma. You can manage your categories feed from")," ",wp.element.createElement("a",{href:"edit.php?post_type=feedzy_categories",title:W("feedzy categories "),target:"_blank"},W("here ")))])),!("fetched"!==this.state.route||void 0===this.props.attributes.feedData)&&wp.element.createElement("div",{className:"feedzy-rss"},this.props.attributes.feed_title&&null!==this.props.attributes.feedData.channel&&wp.element.createElement("div",{className:"rss_header"},wp.element.createElement("h2",null,wp.element.createElement("a",{className:"rss_title"},L(this.props.attributes.feedData.channel.title)),wp.element.createElement("span",{className:"rss_description"}," "+L(this.props.attributes.feedData.channel.description)))),wp.element.createElement("ul",{className:"feedzy-".concat(this.props.attributes.template)},(e=this.props.attributes.feedData.items,t=this.props.attributes.sort,r=P(this.props.attributes.keywords_title),a=P(this.props.attributes.keywords_ban),n=this.props.attributes.max,o=this.props.attributes.offset,s=this.props.attributes.keywords_inc_on,i=this.props.attributes.keywords_exc_on,l=this.props.attributes.from_datetime,u=this.props.attributes.to_datetime,s="author"===s?"creator":s,i="author"===i?"creator":i,l=""!==l&&void 0!==l&&moment(l).format("X"),u=""!==u&&void 0!==u&&moment(u).format("X"),e=Array.from(e).sort((function(e,r){var a,n;return"date_desc"===t||"date_asc"===t?(a=e.pubDate,n=r.pubDate):"title_desc"!==t&&"title_asc"!==t||(a=e.title.toUpperCase(),n=r.title.toUpperCase()),a<n?"date_desc"===t||"title_desc"===t?1:-1:a>n?"date_desc"===t||"title_desc"===t?-1:1:0})).filter((function(e){return!r||r.test(e[s])})).filter((function(e){return!a||!a.test(e[i])})).filter((function(e){var t=e.date+" "+e.time;return t=moment(new Date(t)).format("X"),!l||!u||l<=t&&t<=u})).slice(o,n+o)).map((function(e,t){var r=(e.date||"")+" "+(e.time||"")+" UTC +0000",a=L(e.date)||"",n=L(e.time)||"",o=L(e.categories)||"";if(p.metaExists("tz=local")){var s=new Date(r);s=s.toUTCString(),a=moment.utc(s).format("MMMM D, YYYY"),n=moment.utc(s).format("h:mm A")}var i=e.creator&&p.metaExists("author")?e.creator:"";""!==p.props.attributes.multiple_meta&&"no"!==p.props.attributes.multiple_meta&&((p.multipleMetaExists("source")||p.multipleMetaExists("yes"))&&""!==i&&""!==e.source?i=i+" ("+e.source+")":(p.multipleMetaExists("source")||p.multipleMetaExists("yes"))&&""!==e.source&&(i=e.source)),""===e.thumbnail&&"auto"===p.props.attributes.thumb&&(e.thumbnail=e.default_img);var l=new Object;return l.author=W("by")+" "+i,l.date=W("on")+" "+L(a),l.time=W("at")+" "+L(n),l.categories=W("in")+" "+L(o),wp.element.createElement("li",{key:t,style:{padding:"15px 0 25px"},className:"rss_item feedzy-rss-col-".concat(p.props.attributes.columns)},(e.thumbnail&&"auto"===p.props.attributes.thumb||"yes"===p.props.attributes.thumb)&&wp.element.createElement("div",{className:"rss_image",style:{width:p.props.attributes.size+"px",height:p.props.attributes.size+"px"}},wp.element.createElement("a",{title:L(e.title),style:{width:p.props.attributes.size+"px",height:p.props.attributes.size+"px"}},wp.element.createElement("span",{className:"fetched",style:{width:p.props.attributes.size+"px",height:p.props.attributes.size+"px",backgroundImage:p.getImageURL(e,!0)},title:L(e.title)}))),wp.element.createElement("div",{className:"rss_content_wrap"},0!==p.props.attributes.title?wp.element.createElement("span",{className:"title"},wp.element.createElement("a",null,p.props.attributes.title&&L(e.title).length>p.props.attributes.title?L(e.title).substring(0,p.props.attributes.title)+"...":L(e.title))):"",wp.element.createElement("div",{className:"rss_content"},"no"!==p.props.attributes.metafields&&wp.element.createElement("small",{className:"meta"},function(e,t){var r="";""===t&&(t="author, date, time");for(var a=t.replace(/\s/g,"").split(","),n=0;n<a.length;n++)void 0!==e[a[n]]&&(r=r+" "+e[a[n]]);return r}(l,p.props.attributes.metafields)),p.props.attributes.summary&&wp.element.createElement("p",{className:"description"},p.props.attributes.summarylength&&L(e.description).length>p.props.attributes.summarylength?L(e.description).substring(0,p.props.attributes.summarylength)+" [...]":L(e.description)),feedzyjs.isPro&&e.media&&e.media.src&&wp.element.createElement("audio",{controls:!0,controlsList:"nodownload"},wp.element.createElement("source",{src:e.media.src,type:e.media.type}),W("Your browser does not support the audio element. But you can check this for the original link: "),wp.element.createElement("a",{href:e.media.src},e.media.src)),feedzyjs.isPro&&p.props.attributes.price&&e.price&&"default"!==p.props.attributes.template&&wp.element.createElement("div",{className:"price-wrap"},wp.element.createElement("a",null,wp.element.createElement("button",{className:"price"},e.price))))))}))))]}}])&&K(t.prototype,r),a&&K(t,a),l}(G)),se=wp.i18n.__,ie=wp.blocks.registerBlockType;t.default=ie("feedzy-rss-feeds/feedzy-block",{title:se("Feedzy RSS Feeds"),category:"common",icon:"rss",keywords:[se("Feedzy RSS Feeds"),se("RSS"),se("Feeds")],supports:{html:!1},attributes:a,edit:oe,save:function(){return null}})}]);