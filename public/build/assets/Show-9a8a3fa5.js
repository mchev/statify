import{_ as p}from"./AppLayout-54c5ffce.js";import c from"./DeleteUserForm-b6015817.js";import l from"./LogoutOtherBrowserSessionsForm-39561163.js";import{S as s}from"./SectionBorder-6f9a7bb4.js";import f from"./TwoFactorAuthenticationForm-7de32a1b.js";import u from"./UpdatePasswordForm-2b703780.js";import d from"./UpdateProfileInformationForm-f7b4b0f0.js";import{o,c as _,w as n,a as i,d as r,b as t,e as a,F as h}from"./app-d28c9cbf.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./Modal-3ae87d6a.js";import"./SectionTitle-b5c745df.js";import"./DangerButton-f1c1620b.js";import"./DialogModal-047cd54c.js";import"./InputError-fe6f14f5.js";import"./SecondaryButton-917885c8.js";import"./TextInput-42fdc61c.js";import"./ActionMessage-67d66a49.js";import"./PrimaryButton-6e6aac2f.js";import"./InputLabel-147dc215.js";import"./FormSection-562da7f1.js";const g=i("h2",{class:"font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"}," Profile ",-1),$={class:"max-w-7xl mx-auto py-10 sm:px-6 lg:px-8"},k={key:0},w={key:1},y={key:2},H={__name:"Show",props:{confirmsTwoFactorAuthentication:Boolean,sessions:Array},setup(m){return(e,x)=>(o(),_(p,{title:"Profile"},{header:n(()=>[g]),default:n(()=>[i("div",null,[i("div",$,[e.$page.props.jetstream.canUpdateProfileInformation?(o(),r("div",k,[t(d,{user:e.$page.props.auth.user},null,8,["user"]),t(s)])):a("",!0),e.$page.props.jetstream.canUpdatePassword?(o(),r("div",w,[t(u,{class:"mt-10 sm:mt-0"}),t(s)])):a("",!0),e.$page.props.jetstream.canManageTwoFactorAuthentication?(o(),r("div",y,[t(f,{"requires-confirmation":m.confirmsTwoFactorAuthentication,class:"mt-10 sm:mt-0"},null,8,["requires-confirmation"]),t(s)])):a("",!0),t(l,{sessions:m.sessions,class:"mt-10 sm:mt-0"},null,8,["sessions"]),e.$page.props.jetstream.hasAccountDeletionFeatures?(o(),r(h,{key:3},[t(s),t(c,{class:"mt-10 sm:mt-0"})],64)):a("",!0)])])]),_:1}))}};export{H as default};
