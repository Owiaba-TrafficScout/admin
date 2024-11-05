import{d as y,r as i,T as g,g as c,b as o,a as r,u as a,w,E as v,f as x,o as f,e as V,h as k}from"./app-C5IldEcd.js";import{_ as l,a as d}from"./TextInput.vue_vue_type_script_setup_true_lang-COyazjSy.js";import{_ as u}from"./InputLabel.vue_vue_type_script_setup_true_lang-BuhHF0Xy.js";import{P}from"./PrimaryButton-BLr2sEaB.js";const b={class:"flex items-center gap-4"},S={key:0,class:"text-sm text-gray-600 dark:text-gray-400"},I=y({__name:"UpdatePasswordForm",setup(C){const p=i(null),m=i(null),s=g({current_password:"",password:"",password_confirmation:""}),_=()=>{s.put(route("password.update"),{preserveScroll:!0,onSuccess:()=>{s.reset()},onError:()=>{var n,e;s.errors.password&&(s.reset("password","password_confirmation"),(n=p.value)==null||n.focus()),s.errors.current_password&&(s.reset("current_password"),(e=m.value)==null||e.focus())}})};return(n,e)=>(f(),c("section",null,[e[4]||(e[4]=o("header",null,[o("h2",{class:"text-lg font-medium text-gray-900 dark:text-gray-100"}," Update Password "),o("p",{class:"mt-1 text-sm text-gray-600 dark:text-gray-400"}," Ensure your account is using a long, random password to stay secure. ")],-1)),o("form",{onSubmit:x(_,["prevent"]),class:"mt-6 space-y-6"},[o("div",null,[r(u,{for:"current_password",value:"Current Password"}),r(l,{id:"current_password",ref_key:"currentPasswordInput",ref:m,modelValue:a(s).current_password,"onUpdate:modelValue":e[0]||(e[0]=t=>a(s).current_password=t),type:"password",class:"mt-1 block w-full",autocomplete:"current-password"},null,8,["modelValue"]),r(d,{message:a(s).errors.current_password,class:"mt-2"},null,8,["message"])]),o("div",null,[r(u,{for:"password",value:"New Password"}),r(l,{id:"password",ref_key:"passwordInput",ref:p,modelValue:a(s).password,"onUpdate:modelValue":e[1]||(e[1]=t=>a(s).password=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),r(d,{message:a(s).errors.password,class:"mt-2"},null,8,["message"])]),o("div",null,[r(u,{for:"password_confirmation",value:"Confirm Password"}),r(l,{id:"password_confirmation",modelValue:a(s).password_confirmation,"onUpdate:modelValue":e[2]||(e[2]=t=>a(s).password_confirmation=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),r(d,{message:a(s).errors.password_confirmation,class:"mt-2"},null,8,["message"])]),o("div",b,[r(P,{disabled:a(s).processing},{default:w(()=>e[3]||(e[3]=[V("Save")])),_:1},8,["disabled"]),r(v,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:w(()=>[a(s).recentlySuccessful?(f(),c("p",S," Saved. ")):k("",!0)]),_:1})])],32)]))}});export{I as _};
