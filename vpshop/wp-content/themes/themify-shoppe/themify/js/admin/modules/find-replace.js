let TF_Replace;((t,i)=>{"use strict";let a,e=null,o=0,n=100;const r=new Map,c=()=>t.loadJs(t.url+"js/admin/notification",!!i.TF_Notification),f=async(i,a,e,o)=>{const n=r.get(a);if(n)return n;const c=await t.fetch({action:"tb_get_ajax_builder_posts",page:a,nonce:i});if(!c.success)throw c.data;return r.set(a,c.data),!o&&e>=a+1&&f(i,a+1,e,1),c.data},l=(t,i,a)=>{for(let o in a)if("element_id"!==o&&"mod_name"!==o&&!0!==a[o]&&"px"!==a[o]&&"%"!==a[o]&&a[o]&&isNaN(a[o]))if(Array.isArray(a[o])||"object"==typeof a[o])l(t,i,a[o]);else if("string"==typeof a[o]){let n=(""+a[o]).trim();n.includes(t)&&(e=!0,a[o]=n.replaceAll(t,i))}return a},s=async(t,i,n,r)=>{o+=n.length,await c();const f=[];await TF_Notification.show("info",u(a.searching,t,n,o,r),1200);for(let a=n.length-1;a>-1;--a){e=!1;let o=l(t,i,n[a].data);!0===e&&f.push({data:o,title:n[a].title,id:n[a].id})}return f},w=t=>(t.preventDefault(),t.returnValue="Are you sure"),_=t=>{const i=[];for(let a in t){i.push(t[a].title?t[a].title:t[a])}return i.join(", ")},u=(t,i,a,e,o)=>{const n={posts:a?_(a):"",total:o,count:e,find:i};for(let i in n)void 0!==n[i]&&(t=t.replaceAll("%"+i+"%",n[i]));return t.length>140&&(t=t.slice(0,140)+"..."),t},h=async t=>{try{if("/"!==t[0]&&"/"!==t[1]){if(0!==t.indexOf("http"))throw"";"http://"!==t&&"https://"!==t&&new URL(t)}}catch(i){throw a.wrong_url.replaceAll("%url%",t)}},p=(i,a)=>new Promise(((e,o)=>{setTimeout((()=>{t.fetch({action:"tb_save_ajax_builder_mutiple_posts",nonce:a,data:i}).then((t=>{if(!t.success)return o(t.data);e(t)})).catch(o)}),n)}));TF_Replace=async(e,l,_)=>{await c();try{i.tfOff("beforeunload",w).tfOn("beforeunload",w),o=0,r.clear();const c=await f(_,1,0),y=c.pages,d=c.total;if(a=c.labels,e===l)throw a.same_url;await Promise.all([h(e),h(l)]);const T=await s(e,l,c.posts,d);for(let t=2;t<=y;++t)try{let i=await f(_,t,y),a=await s(e,l,i.posts,d);T.push(...a)}catch(t){}if(T.length>0){T.length>12&&(n=150),await TF_Notification.show("info",u(a.found,e,T,T.length,d),3e3);const i=[],o=[],r=5,c=T.length;let f=!1,l=0;while(T.length>0){let t=T.splice(0,r),a={},e={};for(let i=t.length-1;i>-1;--i){let o=t[i].id;a[o]=t[i].data,e[o]=t[i].title}i.push(a),o.push(e)}for(let t=0,n=i.length;t<n;t++){let n;l+=Object.keys(o[t]).length,await TF_Notification.show("info",u(a.saving,e,o[t],l,c));try{if(n=await p(i[t],_),!n.success)throw n.data}catch(a){try{if(n=await p(new Blob([JSON.stringify(i[t])],{type:"application/json"}),_),!n.success)throw n.data}catch(t){await TF_Notification.showHide("error",t,2e3)}}if(n&&n.data){n=n.data;let t=[];for(let i in n)1!=n[i]?t.push(n[i]):f=!0;t.length>0&&await TF_Notification.showHide("error",u(a.no_found,e,t,t.length,d),4e3)}}!0===f&&await t.fetch({action:"themify_regenerate_css_files_ajax",nonce:_}),await TF_Notification.showHide("done",a.done)}else await TF_Notification.showHide("warning",u(a.no_found,e),3e3)}catch(t){await TF_Notification.showHide("error",t)}i.tfOff("beforeunload",w)}})(Themify,window);