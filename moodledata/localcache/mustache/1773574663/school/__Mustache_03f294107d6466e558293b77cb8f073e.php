<?php

class __Mustache_03f294107d6466e558293b77cb8f073e extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $value = $this->resolveValue($context->findDot('output.doctype'), $context);
        $buffer .= $indent . ($value === null ? '' : $value);
        $buffer .= '
';
        $buffer .= $indent . '<html lang="en" ';
        $value = $this->resolveValue($context->findDot('output.htmlattributes'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '>
';
        $buffer .= $indent . '<head>
';
        $buffer .= $indent . '    <meta charset="utf-8"/>
';
        $buffer .= $indent . '    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
';
        $buffer .= $indent . '    <title>';
        $value = $this->resolveValue($context->findDot('output.page_title'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '</title>
';
        $buffer .= $indent . '    <link rel="shortcut icon" href="';
        $value = $this->resolveValue($context->findDot('output.favicon'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '" />
';
        $buffer .= $indent . '    ';
        $value = $this->resolveValue($context->findDot('output.standard_head_html'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '
';
        $buffer .= $indent . '    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
';
        $buffer .= $indent . '    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
';
        $buffer .= $indent . '    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
';
        $buffer .= $indent . '    <script id="tailwind-config">
';
        $buffer .= $indent . '      tailwind.config = {
';
        $buffer .= $indent . '        darkMode: "class",
';
        $buffer .= $indent . '        theme: {
';
        $buffer .= $indent . '          extend: {
';
        $buffer .= $indent . '            colors: {
';
        $buffer .= $indent . '              "tertiary-fixed-dim": "#e7a407",
';
        $buffer .= $indent . '              "surface-tint": "#9c3f00",
';
        $buffer .= $indent . '              "background": "#f6f6f6",
';
        $buffer .= $indent . '              "on-tertiary-fixed-variant": "#5b3e00",
';
        $buffer .= $indent . '              "surface-container-low": "#f1f1f1",
';
        $buffer .= $indent . '              "surface-container-highest": "#dddddd",
';
        $buffer .= $indent . '              "tertiary-fixed": "#f7b21f",
';
        $buffer .= $indent . '              "surface": "#f6f6f6",
';
        $buffer .= $indent . '              "error-container": "#f95630",
';
        $buffer .= $indent . '              "on-surface-variant": "#5b5b5b",
';
        $buffer .= $indent . '              "outline-variant": "#adadad",
';
        $buffer .= $indent . '              "on-primary-fixed": "#000000",
';
        $buffer .= $indent . '              "inverse-surface": "#0e0e0e",
';
        $buffer .= $indent . '              "surface-container-high": "#e2e2e2",
';
        $buffer .= $indent . '              "tertiary-container": "#f7b21f",
';
        $buffer .= $indent . '              "inverse-on-surface": "#9d9d9d",
';
        $buffer .= $indent . '              "surface-dim": "#d4d4d4",
';
        $buffer .= $indent . '              "on-secondary-fixed-variant": "#5c5b5b",
';
        $buffer .= $indent . '              "on-tertiary-container": "#4f3600",
';
        $buffer .= $indent . '              "surface-bright": "#f6f6f6",
';
        $buffer .= $indent . '              "secondary-fixed": "#e5e2e1",
';
        $buffer .= $indent . '              "surface-container-lowest": "#ffffff",
';
        $buffer .= $indent . '              "on-primary-container": "#401500",
';
        $buffer .= $indent . '              "on-secondary": "#f5f2f1",
';
        $buffer .= $indent . '              "secondary-dim": "#504f4f",
';
        $buffer .= $indent . '              "on-background": "#2f2f2f",
';
        $buffer .= $indent . '              "on-primary": "#fff0ea",
';
        $buffer .= $indent . '              "primary-container": "#ff7a2f",
';
        $buffer .= $indent . '              "primary": "#9c3f00",
';
        $buffer .= $indent . '              "on-primary-fixed": "#000000",
';
        $buffer .= $indent . '              "primary-dim": "#893600",
';
        $buffer .= $indent . '              "on-tertiary-fixed": "#342200",
';
        $buffer .= $indent . '              "on-secondary-container": "#525151",
';
        $buffer .= $indent . '              "secondary-container": "#e5e2e1",
';
        $buffer .= $indent . '              "outline": "#777777",
';
        $buffer .= $indent . '              "on-surface": "#2f2f2f",
';
        $buffer .= $indent . '              "surface-container": "#e8e8e8",
';
        $buffer .= $indent . '              "inverse-primary": "#fe6b00",
';
        $buffer .= $indent . '              "tertiary-dim": "#6b4900",
';
        $buffer .= $indent . '              "error": "#b02500",
';
        $buffer .= $indent . '              "secondary": "#5c5b5b",
';
        $buffer .= $indent . '              "on-primary-fixed-variant": "#4f1c00",
';
        $buffer .= $indent . '              "on-error": "#ffefec",
';
        $buffer .= $indent . '              "secondary-fixed-dim": "#d6d4d3",
';
        $buffer .= $indent . '              "on-error-container": "#520c00",
';
        $buffer .= $indent . '              "primary-fixed-dim": "#f66700",
';
        $buffer .= $indent . '              "tertiary": "#7a5400",
';
        $buffer .= $indent . '              "surface-variant": "#dddddd",
';
        $buffer .= $indent . '              "error-dim": "#b92902",
';
        $buffer .= $indent . '              "primary-fixed": "#ff7a2f",
';
        $buffer .= $indent . '              "on-tertiary": "#fff1df"
';
        $buffer .= $indent . '            },
';
        $buffer .= $indent . '            fontFamily: {
';
        $buffer .= $indent . '              "headline": ["Manrope"],
';
        $buffer .= $indent . '              "body": ["Inter"],
';
        $buffer .= $indent . '              "label": ["Inter"]
';
        $buffer .= $indent . '            },
';
        $buffer .= $indent . '            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
';
        $buffer .= $indent . '          },
';
        $buffer .= $indent . '        },
';
        $buffer .= $indent . '      }
';
        $buffer .= $indent . '    </script>
';
        $buffer .= $indent . '    <style>
';
        $buffer .= $indent . '        .material-symbols-outlined {
';
        $buffer .= $indent . '            font-variation-settings: \'FILL\' 0, \'wght\' 400, \'GRAD\' 0, \'opsz\' 24;
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '        .kinetic-gradient {
';
        $buffer .= $indent . '            background: linear-gradient(135deg, #9c3f00 0%, #ff7a2f 100%);
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '        .pattern-bg {
';
        $buffer .= $indent . '            background-color: #f6f6f6;
';
        $buffer .= $indent . '            background-image: radial-gradient(#dddddd 0.5px, transparent 0.5px);
';
        $buffer .= $indent . '            background-size: 24px 24px;
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '    </style>
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<body class="font-body text-on-surface bg-background min-h-screen flex flex-col" ';
        $value = $this->resolveValue($context->find('bodyattributes'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '>
';
        if ($partial = $this->mustache->loadPartial('core/local/toast/wrapper')) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- TopNavBar Shared Component -->
';
        $buffer .= $indent . '<nav class="sticky top-0 z-50 bg-surface-container-lowest/80 backdrop-blur-md border-b border-outline-variant/10">
';
        $buffer .= $indent . '    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
';
        $buffer .= $indent . '        <div class="flex items-center gap-8">
';
        $buffer .= $indent . '            <span class="font-headline text-2xl font-extrabold tracking-tight text-on-surface">';
        $value = $this->resolveValue($context->find('sitename'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '</span>
';
        $buffer .= $indent . '            <div class="hidden md:flex items-center gap-6">
';
        $buffer .= $indent . '                <a class="text-sm font-medium hover:text-primary transition-colors" href="';
        $value = $this->resolveValue($context->findDot('output.wwwroot'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '/">Home</a>
';
        $buffer .= $indent . '                <a class="text-sm font-medium hover:text-primary transition-colors" href="#">';
        $value = $context->find('str');
        $buffer .= $this->sectionF870cf92426deaef3e90c68f33111c89($context, $indent, $value);
        $buffer .= '</a>
';
        $buffer .= $indent . '                <a class="text-sm font-medium hover:text-primary transition-colors" href="#">';
        $value = $context->find('str');
        $buffer .= $this->section88bf81f29ac23c3b0ac328036b4c7780($context, $indent, $value);
        $buffer .= '</a>
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '        <div class="flex items-center gap-4">
';
        $buffer .= $indent . '            <button class="p-2 rounded-full hover:bg-surface-container-high transition-colors">
';
        $buffer .= $indent . '                <span class="material-symbols-outlined text-on-surface-variant">language</span>
';
        $buffer .= $indent . '            </button>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</nav>
';
        $buffer .= $indent . '
';
        $value = $this->resolveValue($context->findDot('output.standard_top_of_body_html'), $context);
        $buffer .= $indent . ($value === null ? '' : $value);
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Main Content Canvas -->
';
        $buffer .= $indent . '<main class="flex-grow pattern-bg flex items-center justify-center py-16 px-6">
';
        $buffer .= $indent . '    <div class="w-full max-auto max-w-[440px] relative">
';
        $buffer .= $indent . '        <!-- Asymmetric Accent Element -->
';
        $buffer .= $indent . '        <div class="absolute -top-12 -right-12 w-32 h-32 kinetic-gradient opacity-10 blur-3xl rounded-full"></div>
';
        $buffer .= $indent . '        <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-tertiary-fixed opacity-5 blur-3xl rounded-full"></div>
';
        $buffer .= $indent . '        <!-- Login Card -->
';
        $buffer .= $indent . '        <div class="relative bg-surface-container-lowest shadow-[0_32px_64px_-12px_rgba(0,0,0,0.06)] rounded-xl overflow-hidden p-8 md:p-12">
';
        $buffer .= $indent . '            ';
        $value = $this->resolveValue($context->findDot('output.main_content'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</main>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Footer Shared Component -->
';
        $buffer .= $indent . '<footer class="bg-surface py-12 px-6 border-t border-outline-variant/10">
';
        $buffer .= $indent . '    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
';
        $buffer .= $indent . '        <div class="flex flex-col items-center md:items-start gap-2">
';
        $buffer .= $indent . '            <span class="font-headline font-bold text-lg text-on-surface">';
        $value = $this->resolveValue($context->find('sitename'), $context);
        $buffer .= ($value === null ? '' : $value);
        $buffer .= '</span>
';
        $buffer .= $indent . '            <p class="text-sm text-on-surface-variant">';
        $value = $context->find('str');
        $buffer .= $this->section3cef0c729bd31199c0f96ce94b38f287($context, $indent, $value);
        $buffer .= '</p>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '        <div class="flex flex-wrap justify-center gap-x-8 gap-y-4">
';
        $buffer .= $indent . '            <a class="text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors" href="#">';
        $value = $context->find('str');
        $buffer .= $this->section3b68523e4e1d6bd8a165f9af97c2e3be($context, $indent, $value);
        $buffer .= '</a>
';
        $buffer .= $indent . '            <a class="text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors" href="#">';
        $value = $context->find('str');
        $buffer .= $this->sectionEbb8e7659e78a173ede89f075cf245ac($context, $indent, $value);
        $buffer .= '</a>
';
        $buffer .= $indent . '            <a class="text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors" href="#">';
        $value = $context->find('str');
        $buffer .= $this->section2ff95bd097ee2ac609895954971e3e3b($context, $indent, $value);
        $buffer .= '</a>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '        <div class="flex items-center gap-4">
';
        $buffer .= $indent . '            <a class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container transition-all" href="#">
';
        $buffer .= $indent . '                <span class="material-symbols-outlined text-xl">help</span>
';
        $buffer .= $indent . '            </a>
';
        $buffer .= $indent . '            <a class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container transition-all" href="#">
';
        $buffer .= $indent . '                <span class="material-symbols-outlined text-xl">forum</span>
';
        $buffer .= $indent . '            </a>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</footer>
';
        $buffer .= $indent . '
';
        $value = $this->resolveValue($context->findDot('output.standard_end_of_body_html'), $context);
        $buffer .= $indent . ($value === null ? '' : $value);
        $buffer .= '
';
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>
';
        $value = $context->find('js');
        $buffer .= $this->sectionC1c7ccc3f8b683fc1ccb1c6bfec9274d($context, $indent, $value);

        return $buffer;
    }

    private function sectionF870cf92426deaef3e90c68f33111c89(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'courses';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'courses';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section88bf81f29ac23c3b0ac328036b4c7780(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'support';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'support';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3cef0c729bd31199c0f96ce94b38f287(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'poweredbymoodle, core';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'poweredbymoodle, core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3b68523e4e1d6bd8a165f9af97c2e3be(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'privacypolicy, core';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'privacypolicy, core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionEbb8e7659e78a173ede89f075cf245ac(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'termsofservice, core';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'termsofservice, core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2ff95bd097ee2ac609895954971e3e3b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'help';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'help';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC1c7ccc3f8b683fc1ccb1c6bfec9274d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
M.util.js_pending(\'theme_boost/loader\');
require([\'theme_boost/loader\'], function() {
  M.util.js_complete(\'theme_boost/loader\');
});
';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . 'M.util.js_pending(\'theme_boost/loader\');
';
                $buffer .= $indent . 'require([\'theme_boost/loader\'], function() {
';
                $buffer .= $indent . '  M.util.js_complete(\'theme_boost/loader\');
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
