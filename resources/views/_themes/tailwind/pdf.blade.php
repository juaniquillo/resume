<?php

/*
 * LEGEND
 * 
 * Main colors: text-gray-900 | border-gray-100 | border-gray-200
 * Text: text-gray-700 | text-gray-500 | text-gray-400
 * Title: text-sm | font-bold | uppercase | tracking-[0.2em]
 * Links: text-gray-900 | underline
 */

return [
    'cover-letter-container' => 'bg-white px-4 pt-1 pb-2 prose max-w-none text-gray-700 text-sm prose-headings:font-bold prose-headings:text-gray-900 prose-a:text-gray-900 prose-a:text-gray-900 prose-a:underline',
    
    'container' => 'mx-auto max-w-[800px] my-2 px-10 py-10 bg-white text-gray-900 border-0',
    
    'basics-container' => 'mb-8 border-b-2 border-gray-100 pb-8',
    'image-container' => 'hidden', // Usually images are better omitted or very small in strict PDF resumes
    'image' => 'w-20 h-20 rounded-lg object-cover',
    'name' => 'text-4xl font-bold tracking-tight text-gray-900 mb-1',
    'label' => 'text-xl font-medium text-gray-600 mb-4',
    
    'contact-container' => 'flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-600',
    'links' => 'text-gray-900 hover:text-black transition-colors underline decoration-gray-200',
    'contact-item' => 'flex items-center gap-1.5',
    'icon' => 'size-3 text-gray-400',
    
    'section' => 'mb-8 last:mb-0',
    'section-title' => 'text-sm font-bold text-gray-900 mb-4 uppercase tracking-[0.2em] border-b border-gray-200 pb-2',
    
    'summary-container' => '',
    'summary' => 'text-[15px] leading-relaxed text-gray-700 whitespace-pre-wrap',
    
    'work-container' => '',
    'volunteers-container' => '',
    'education-container' => '',
    'awards-container' => '',
    'certificates-container' => '',
    'publications-container' => '',
    'skills-container' => '',
    'languages-container' => 'flex flex-wrap gap-4',
    'interests-container' => '',
    'references-container' => '',
    'projects-container' => '',

    'item-container' => 'mb-6 last:mb-0',
    'item-title' => 'text-[16px] font-bold text-gray-900 mb-0.5',
    'item-details' => 'text-gray-500 mb-2 text-sm font-medium flex flex-wrap gap-x-4 items-center',
    
    'list' => 'space-y-1.5 text-gray-700 text-[14px] list-disc list-inside leading-snug',
    'list-item' => 'pl-2',
    
    'badge' => 'inline-block text-[13px] font-medium text-gray-700 mr-2',
    'social-badge' => 'inline-flex items-center gap-1.5 text-sm text-gray-600',
    'date' => 'text-gray-400 font-normal',
    'subtitle' => 'text-[15px] font-semibold text-gray-800 mb-1',
];
