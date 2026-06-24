<?php

return [
    'container' => 'mx-4 md:mx-auto max-w-5xl px-4 md:px-8 py-12 md:py-20 bg-black text-green-400 border-2 border-green-500 rounded-lg shadow-[0_0_30px_rgba(34,197,94,0.25)] my-6 md:my-6 font-[IBM_Plex_Mono] relative pt-[3.5rem] overflow-hidden before:content-["●_●_●__bash_-_guest@resume:~"] before:absolute before:top-0 before:left-0 before:right-0 before:h-[2.2rem] before:bg-[#161616] before:text-[#888888] before:text-[0.8rem] before:leading-[2.2rem] before:pl-[1rem] before:border-b-2 before:border-green-500 before:font-[IBM_Plex_Mono] before:tracking-[0.05em] before:z-20 after:content-[""] after:absolute after:inset-0 after:bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%)] after:bg-[length:100%_4px] after:z-10 after:pointer-events-none after:opacity-[0.15] print:bg-white print:text-black print:border-0 print:shadow-none print:my-0 print:py-0 print:px-0',
    
    'basics-container' => 'mb-16 md:mb-24 flex flex-col items-center md:items-start border-b-2 border-green-500 pb-12 print:border-b-2 print:border-black',
    'image-container' => 'mb-8 md:mb-12',
    'image' => 'w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 border-2 border-green-500 object-cover shadow-[4px_4px_0px_rgba(34,197,94,0.3)] max-w-full print:border-black print:shadow-none',
    'name' => 'text-4xl md:text-6xl lg:text-7xl font-bold uppercase tracking-tight mb-2 break-words text-green-400 print:text-black',
    'label' => 'text-lg md:text-2xl font-bold text-cyan-400 px-2 py-0.5 inline-block uppercase tracking-wider mt-2 mb-6 border border-cyan-500 bg-cyan-950/20 print:text-black print:border-black print:bg-white',
    
    'contact-container' => 'flex flex-wrap gap-x-4 md:gap-x-6 gap-y-4 text-base md:text-lg font-bold mt-2 print:text-black',
    'links' => 'text-cyan-400 hover:text-green-400 transition-colors underline decoration-1 underline-offset-4 decoration-cyan-500 hover:decoration-green-500 print:text-black print:no-underline',
    'contact-item' => 'flex items-center gap-2 font-bold text-green-400 print:text-black',
    
    'email-item' => 'flex items-center gap-2 font-bold text-green-400 before:content-["[email]_"] before:text-cyan-400 print:text-black',
    'phone-item' => 'flex items-center gap-2 font-bold text-green-400 before:content-["[phone]_"] before:text-cyan-400 print:text-black',
    'url-item' => 'flex items-center gap-2 font-bold text-green-400 before:content-["[web]_"] before:text-cyan-400 print:text-black',
    'location-item' => 'flex items-center gap-2 font-bold text-green-400 before:content-["[addr]_"] before:text-cyan-400 print:text-black',
    'icon' => 'hidden',
    
    'section' => 'mb-14 md:mb-24 last:mb-0 print:mb-12',
    'section-title' => 'text-2xl md:text-4xl font-bold uppercase tracking-wide mb-8 md:mb-12 text-green-400 border-b border-green-500/30 pb-2 before:content-["guest@resume:~$_cat_"] before:text-green-500 before:font-normal after:content-["_.txt"] after:text-green-500 after:font-normal print:text-black print:border-black',
    
    'summary-container' => 'border-l-2 border-green-500 pl-6 md:pl-8 print:border-black',
    'summary' => 'text-lg md:text-xl leading-relaxed text-green-300 max-w-4xl whitespace-pre-wrap print:text-black',
    
    'work-container' => '',
    'volunteers-container' => '',
    'education-container' => '',
    'awards-container' => '',
    'certificates-container' => '',
    'publications-container' => '',
    'skills-container' => '',
    'languages-container' => 'flex flex-wrap gap-4 md:gap-6',
    'interests-container' => '',
    'references-container' => '',
    'projects-container' => '',
    'downloads-container' => 'flex flex-wrap gap-4',

    'item-container' => 'mb-10 md:mb-12 last:mb-0 relative group',
    'item-title' => 'text-xl md:text-2xl font-bold uppercase tracking-tight text-green-400 mb-2 group-hover:text-cyan-400 transition-colors break-words print:text-black',
    'item-details' => 'text-cyan-400 mb-4 text-sm md:text-base font-bold uppercase tracking-wider flex flex-wrap gap-x-4 md:gap-x-6 gap-y-2 items-center print:text-black',
    
    'list' => 'space-y-2 text-green-300 font-medium text-base md:text-lg leading-relaxed list-none print:text-black',
    'list-item' => 'before:content-[">_"] before:text-green-500 before:mr-2 print:before:content-["•_"] print:before:text-black',
    
    'badge' => 'px-2 py-0.5 text-xs font-bold uppercase tracking-wider bg-green-950/40 text-green-400 border border-green-500 hover:bg-green-500 hover:text-black transition-colors print:bg-white print:text-black print:border-black',
    'keyword-badge' => 'px-2 py-0.5 text-xs font-bold uppercase tracking-wider bg-cyan-950/40 text-cyan-400 border border-cyan-500 hover:bg-cyan-500 hover:text-black transition-colors print:bg-white print:text-black print:border-black',
    'social-badge' => 'inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-black text-green-400 border border-green-500 hover:bg-green-600 hover:text-black transition-all duration-300 before:content-["[profile]_"] before:text-cyan-400 print:bg-white print:text-black print:border-black',
    'date' => 'text-green-500/80 font-normal print:text-black',
    'subtitle' => 'text-lg md:text-xl font-bold text-green-400 mb-2 uppercase tracking-tight print:text-black',
];
