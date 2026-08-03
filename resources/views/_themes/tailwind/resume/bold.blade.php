<?php

/*
 * LEGEND
 * 
 * Main colors: text-white | text-yellow-400 | bg-black
 * Text: text-zinc-300 | text-zinc-500
 * Title: text-2xl md:text-5xl | font-black | uppercase
 * Links: text-white hover:text-yellow-400 | underline
 */

return [
    'cover-letter-container' => 'prose prose-invert prose-yellow max-w-none text-zinc-300 prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tighter prose-a:text-white prose-a:hover:text-yellow-400 prose-a:underline',

    'container' => 'mx-4 md:mx-auto max-w-5xl px-4 md:px-8 py-12 md:py-20 bg-zinc-900 text-white shadow-[10px_10px_0px_0px_rgba(255,255,0,1)] md:shadow-[20px_20px_0px_0px_rgba(255,255,0,1)] my-6 md:my-6 border-4 border-black dark:bg-black dark:border-zinc-800',
    
    'basics-container' => 'mb-16 md:mb-24 flex flex-col items-center md:items-start border-b-8 border-yellow-400 pb-12',
    'image-container' => 'mb-8 md:mb-12',
    'image' => 'w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 grayscale hover:grayscale-0 transition-all duration-500 border-4 border-white object-cover shadow-[6px_6px_0px_0px_rgba(255,255,0,1)] md:shadow-[10px_10px_0px_0px_rgba(255,255,0,1)] max-w-full',
    'name' => 'text-4xl md:text-6xl lg:text-7xl font-black uppercase tracking-tighter leading-[0.85] mb-2 break-words',
    'label' => 'text-lg md:text-2xl font-bold bg-yellow-400 text-black px-4 py-1 inline-block uppercase italic tracking-widest mt-2 mb-6',
    
    'contact-container' => 'flex flex-wrap gap-x-4 md:gap-x-6 gap-y-4 text-base md:text-lg font-bold mt-2',
    'links' => 'text-white hover:text-yellow-400 transition-colors decoration-2 md:decoration-4 underline underline-offset-8 decoration-yellow-400',
    'contact-item' => 'flex items-center gap-2 font-bold',
    'icon' => 'size-4 text-yellow-400',
    
    'section' => 'mb-16 md:mb-24 last:mb-0',
    'section-title' => 'text-2xl md:text-5xl font-black uppercase tracking-tighter mb-8 md:mb-12 bg-white text-black px-4 md:px-6 py-2 inline-block transform -rotate-2',
    
    'summary-container' => 'border-l-4 md:border-l-8 border-yellow-400 pl-6 md:pl-8',
    'summary' => 'text-xl md:text-2xl leading-tight font-medium text-zinc-300 max-w-4xl whitespace-pre-wrap',
    
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

    'item-container' => 'mb-12 md:mb-16 last:mb-0 relative group',
    'item-title' => 'text-2xl md:text-4xl font-extrabold uppercase tracking-tight text-white mb-2 group-hover:text-yellow-400 transition-colors break-words',
    'item-details' => 'text-yellow-400 mb-4 md:mb-6 text-base md:text-lg font-bold uppercase tracking-widest flex flex-wrap gap-x-4 md:gap-x-6 gap-y-2 items-center',
    
    'list' => 'space-y-3 md:space-y-4 text-zinc-400 font-medium text-lg md:text-xl leading-snug list-none',
    'list-item' => 'before:content-["▶"] before:text-yellow-400 before:mr-3 md:before:mr-4',
    
    'badge' => 'px-3 md:px-4 py-1 text-xs md:text-sm font-black uppercase tracking-widest bg-zinc-800 text-white border-2 border-zinc-700 hover:border-yellow-400 transition-colors',
    'social-badge' => 'inline-flex items-center gap-2 md:gap-2.5 px-3 md:px-4 py-1.5 md:py-2 text-[10px] md:text-xs font-black uppercase tracking-widest bg-white text-black border-2 md:border-4 border-black hover:bg-yellow-400 transition-all transform hover:-translate-y-1 hover:translate-x-1 hover:shadow-none shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]',
    'date' => 'text-zinc-500 font-bold',
    'subtitle' => 'text-xl md:text-2xl font-bold text-white mb-4 uppercase tracking-tight',
];
