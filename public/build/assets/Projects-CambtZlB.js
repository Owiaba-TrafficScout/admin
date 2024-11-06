import{c as f,a as h}from"./App.vue_vue_type_script_setup_true_lang-gctiTYru.js";import{_,a as v}from"./TextInput.vue_vue_type_script_setup_true_lang-DGXYW4dJ.js";import{d as w,Q as a,B as j,r as C,T as P,c as V,w as z,o as c,b as t,u as o,g as d,e as p,a as r,n as m,h as u,t as I}from"./app-COOdjUSe.js";import"./vue3-multiselect.umd.min-BBsq6F1S.js";/**
 * @license lucide-vue-next v0.447.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const B=f("CopyIcon",[["rect",{width:"14",height:"14",x:"8",y:"8",rx:"2",ry:"2",key:"17jyea"}],["path",{d:"M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2",key:"zix9uf"}]]);/**
 * @license lucide-vue-next v0.447.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const N=f("PlusIcon",[["path",{d:"M5 12h14",key:"1ays0h"}],["path",{d:"M12 5v14",key:"s699le"}]]),E={class:"flex flex-col gap-5"},M={class:"mt-2 flex flex-col gap-5"},S=["href"],T={class:"mt-5 flex flex-row gap-5"},Q=w({__name:"Projects",props:{projects:{},roles:{}},setup(g){const x=g,i=a().props.auth.is_tenant_admin;j("roles",x.roles);const n=C(` ml-10 w-32 inline-flex items-center rounded-md border
    border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold
    uppercase tracking-widest text-white transition duration-150 ease-in-out
    hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2
    focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200
    dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white
    dark:focus:ring-offset-gray-800 dark:active:bg-gray-300`),s=P({email:""}),y=()=>{s.post(route("projects.invite",{project:a().props.selected_project.id}),{onSuccess:()=>{s.reset()},onError:l=>{console.log(l)}})},b=async()=>{await navigator.clipboard.writeText(a().props.selected_project.code),alert("Project code copied to clipboard")};return(l,e)=>(c(),V(h,{page:"Project"},{default:z(()=>[t("div",E,[t("div",M,[o(i)?(c(),d("a",{key:0,href:l.route("projects.create"),class:m([n.value,"w-fit"])},[e[1]||(e[1]=p("New Project ")),r(o(N),{class:"ml-2",size:16})],10,S)):u("",!0)]),e[3]||(e[3]=t("h1",{class:"mt-20 text-lg font-semibold capitalize text-black"}," Invite users to this Project ",-1)),t("div",T,[t("div",null,[r(_,{id:"email",type:"text",class:"mt-1 block w-full",modelValue:o(s).email,"onUpdate:modelValue":e[0]||(e[0]=k=>o(s).email=k),required:"",autofocus:"",autocomplete:"email",placeholder:"Email"},null,8,["modelValue"]),r(v,{class:"mt-2",message:o(s).errors.email},null,8,["message"])]),o(i)?(c(),d("button",{key:0,onClick:y,class:m([n.value,"w-fit"])}," Send Invitation ",2)):u("",!0)]),t("div",null,[e[2]||(e[2]=t("h3",{class:"mt-20 text-lg font-semibold capitalize text-black"}," or coppy and share project code 👇 ",-1)),t("p",{onClick:b,class:"my-5 flex cursor-pointer flex-row text-black"},[p(I(o(a)().props.selected_project.code)+" ",1),r(o(B),{class:"ml-2",size:18})])])])]),_:1}))}});export{Q as default};
