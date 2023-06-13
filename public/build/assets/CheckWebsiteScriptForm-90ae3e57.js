import{r as u,o as l,d as p,e as m,a,t as _,T as f,c as h,w as s,f as c,b as i,u as d,n as b}from"./app-2e38f331.js";import{_ as g}from"./FormSection-b7bf18b1.js";import{_ as v}from"./ActionMessage-931473cc.js";import{_ as y}from"./PrimaryButton-aef757a3.js";import"./SectionTitle-dd136050.js";import"./_plugin-vue_export-helper-c27b6911.js";const k={key:0,class:"absolute text-green-500 bottom-full right-2 text-sm"},x={class:"font-mono text-sm whitespace-pre-wrap pr-6"},w=["disabled"],C=a("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-5 h-5"},[a("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"})],-1),V=[C],S={__name:"Clipboard",props:{content:String},setup(r){const o=r,e=u(!1),n=()=>{if(navigator.clipboard)navigator.clipboard.writeText(o.content).then(()=>{e.value=!0,setTimeout(()=>{e.value=!1},2e3)}).catch(t=>{console.error("Failed to copy text: ",t)});else{const t=document.createElement("textarea");t.value=o.content,document.body.appendChild(t),t.select(),document.execCommand("copy"),document.body.removeChild(t),e.value=!0,setTimeout(()=>{e.value=!1},2e3)}};return(t,B)=>(l(),p("div",{onClick:n,class:"relative border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm py-3 px-4 flex justify-between"},[e.value?(l(),p("span",k,"Copied")):m("",!0),a("pre",x,_(r.content),1),a("button",{type:"button",title:"Copy",onClick:n,disabled:e.value},V,8,w)]))}},T={class:"col-span-6"},z={__name:"CheckWebsiteScriptForm",props:{website:Object,script:String},setup(r){const o=f({});return(e,n)=>(l(),h(g,null,{title:s(()=>[c("Tracking Script ")]),description:s(()=>[c(" To include the script on your website, simply copy and paste it between the <head> tags in your site's code. ")]),form:s(()=>[a("div",T,[i(S,{content:r.script},null,8,["content"])])]),actions:s(()=>[i(v,{on:d(o).recentlySuccessful,class:"mr-3"},{default:s(()=>[c(" Saved. ")]),_:1},8,["on"]),i(y,{class:b({"opacity-25":d(o).processing}),disabled:d(o).processing},{default:s(()=>[c(" Check ")]),_:1},8,["class","disabled"])]),_:1}))}};export{z as default};
