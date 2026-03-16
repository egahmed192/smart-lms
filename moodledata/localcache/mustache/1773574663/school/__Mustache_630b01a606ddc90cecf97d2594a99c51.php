<?php

class __Mustache_630b01a606ddc90cecf97d2594a99c51 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="loginform">
';
        $buffer .= $indent . '    <div class="flex flex-col items-center text-center mb-10">
';
        $value = $context->find('logourl');
        $buffer .= $this->section79136dfdd0c3d1e368a6077df6580ef4($context, $indent, $value);
        $value = $context->find('logourl');
        if (empty($value)) {
            
            $buffer .= $indent . '            <div class="w-16 h-16 kinetic-gradient rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-primary/20">
';
            $buffer .= $indent . '                <span class="material-symbols-outlined text-white text-4xl" data-weight="fill">school</span>
';
            $buffer .= $indent . '            </div>
';
        }
        $buffer .= $indent . '        <h1 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-2">';
        $value = $context->find('str');
        $buffer .= $this->section80a4d1690ff171d23b2097c3acda60e4($context, $indent, $value);
        $buffer .= '</h1>
';
        $buffer .= $indent . '        <p class="text-on-surface-variant text-sm">Please enter your credentials to access your courses.</p>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    
';
        $value = $context->find('maintenance');
        $buffer .= $this->section1350777fe2c2f8ed743769bc217048a2($context, $indent, $value);
        $value = $context->find('error');
        $buffer .= $this->section611072a7d92fbab060264653814041d5($context, $indent, $value);
        $value = $context->find('info');
        $buffer .= $this->sectionEe5e509a0023c9c791418604d2af526f($context, $indent, $value);
        $buffer .= $indent . '    
';
        $value = $context->find('showloginform');
        $buffer .= $this->sectionF34206017e1f492810ee735a70feff85($context, $indent, $value);
        $value = $context->find('hasidentityproviders');
        $buffer .= $this->sectionB1695062e04b5e9457492827987e29d1($context, $indent, $value);
        $buffer .= $indent . '    
';
        $value = $context->find('hasinstructions');
        $buffer .= $this->section0a033aee2e25356fb18557434886f27b($context, $indent, $value);
        $buffer .= $indent . '    
';
        $value = $context->find('cansignup');
        $buffer .= $this->section2d7b768e8d01bce5cffd21dc45cc8381($context, $indent, $value);
        $buffer .= $indent . '    
';
        $value = $context->find('canloginasguest');
        $buffer .= $this->section3a290868fb5777d37477438a622763dd($context, $indent, $value);
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    <!-- Help Link Section -->
';
        $buffer .= $indent . '    <div class="mt-10 pt-8 border-t border-outline-variant/10 text-center">
';
        $buffer .= $indent . '        <p class="text-sm text-on-surface-variant mb-4">
';
        $value = $context->find('cansignup');
        $buffer .= $this->section4f859d6cb1a86ba6594d0d47cd961df5($context, $indent, $value);
        $value = $context->find('cansignup');
        if (empty($value)) {
            
            $buffer .= $indent . '                Need an account? 
';
            $buffer .= $indent . '                <a class="text-on-surface font-bold hover:text-primary transition-colors underline underline-offset-4 decoration-primary/30" href="#">';
            $value = $context->find('str');
            $buffer .= $this->section3587dafaae8e35adc97a3429715bae5e($context, $indent, $value);
            $buffer .= '</a>
';
        }
        $buffer .= $indent . '        </p>
';
        $buffer .= $indent . '        <div class="flex items-center justify-center gap-4 mt-4">
';
        $value = $context->find('languagemenu');
        $buffer .= $this->section43ea3d4a9e87b4be73a9b22bf3a75cd4($context, $indent, $value);
        $buffer .= $indent . '            <button type="button" class="text-xs text-on-surface-variant hover:text-on-surface transition-colors underline underline-offset-2" ';
        $buffer .= ' data-modal="alert"';
        $buffer .= ' data-modal-title-str=\'["cookiesenabled", "core"]\' ';
        $buffer .= ' data-modal-content-str=\'["cookiesenabled_help_html", "core"]\'';
        $buffer .= '>';
        $value = $context->find('str');
        $buffer .= $this->sectionFcb729cc74d31bce5e3746aa60b79a2e($context, $indent, $value);
        $buffer .= '</button>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        $value = $context->find('js');
        $buffer .= $this->section7afc41bda62bae2dd1ad0c9d46705ec8($context, $indent, $value);

        return $buffer;
    }

    private function section79136dfdd0c3d1e368a6077df6580ef4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="w-16 h-16 rounded-xl overflow-hidden mb-6 shadow-lg shadow-primary/20">
                <img id="logoimage" src="{{logourl}}" class="w-full h-full object-contain" alt="{{sitename}}"/>
            </div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div class="w-16 h-16 rounded-xl overflow-hidden mb-6 shadow-lg shadow-primary/20">
';
                $buffer .= $indent . '                <img id="logoimage" src="';
                $value = $this->resolveValue($context->find('logourl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" class="w-full h-full object-contain" alt="';
                $value = $this->resolveValue($context->find('sitename'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '"/>
';
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section80a4d1690ff171d23b2097c3acda60e4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' loginto, core, {{sitename}} ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' loginto, core, ';
                $value = $this->resolveValue($context->find('sitename'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= ' ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1350777fe2c2f8ed743769bc217048a2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mb-6 p-4 bg-error-container/10 border border-error-container/30 rounded-lg text-error text-sm" id="loginmaintenance">
            {{{maintenance}}}
        </div>
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <div class="mb-6 p-4 bg-error-container/10 border border-error-container/30 rounded-lg text-error text-sm" id="loginmaintenance">
';
                $buffer .= $indent . '            ';
                $value = $this->resolveValue($context->find('maintenance'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section611072a7d92fbab060264653814041d5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mb-6 p-4 bg-error-container/10 border border-error-container/30 rounded-lg text-error text-sm" id="loginerrormessage" role="alert">{{error}}</div>
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <div class="mb-6 p-4 bg-error-container/10 border border-error-container/30 rounded-lg text-error text-sm" id="loginerrormessage" role="alert">';
                $value = $this->resolveValue($context->find('error'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionEe5e509a0023c9c791418604d2af526f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mb-6 p-4 bg-primary-container/10 border border-primary-container/30 rounded-lg text-on-primary-container text-sm" id="logininfomessage" role="status">{{info}}</div>
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <div class="mb-6 p-4 bg-primary-container/10 border border-primary-container/30 rounded-lg text-on-primary-container text-sm" id="logininfomessage" role="status">';
                $value = $this->resolveValue($context->find('info'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section27e9419edc620e0e1872d2a6521f1533(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' username ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' username ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section22141a6741c33f407ef6171795337eec(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' usernameemail ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' usernameemail ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section95688399ea76ca26a40ecb6df9ae7e86(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        {{#str}} usernameemail {{/str}}
                    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                        ';
                $value = $context->find('str');
                $buffer .= $this->section22141a6741c33f407ef6171795337eec($context, $indent, $value);
                $buffer .= '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFea69428308e6a733cfeebf7670bdc01(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'username';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'username';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section983b6843353faa33a83a9ec3069863a3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'usernameemail';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'usernameemail';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section118ece6c412804f669c845b43ecc9a01(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#cleanstr}}usernameemail{{/cleanstr}}';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $value = $context->find('cleanstr');
                $buffer .= $this->section983b6843353faa33a83a9ec3069863a3($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE056be559d6d01a9bd2bf6f760f8e3e3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' password ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' password ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE3afea308016df7243ba8871f7081e79(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'forgotaccount';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'forgotaccount';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4e50d9b1632f258e8c10be3e2ed759be(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'password';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'password';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section62c301b193fb67b798b9581eaed1c246(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <div class="login-form-recaptcha">
                    <div class="flex justify-center">
                        {{{recaptcha}}}
                    </div>
                </div>
            ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                <div class="login-form-recaptcha">
';
                $buffer .= $indent . '                    <div class="flex justify-center">
';
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->find('recaptcha'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '
';
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '                </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB15dee8971ab065bf4d6402b60d852be(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'login';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'login';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionF34206017e1f492810ee735a70feff85(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <form class="space-y-6" action="{{loginurl}}" method="post" id="login">
            <input id="anchor" type="hidden" name="anchor" value="">
            <script>document.getElementById(\'anchor\').value = location.hash;</script>
            <input type="hidden" name="logintoken" value="{{logintoken}}">
            
            <!-- Username Field -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1" for="username">
                    {{^canloginbyemail}}
                        {{#str}} username {{/str}}
                    {{/canloginbyemail}}
                    {{#canloginbyemail}}
                        {{#str}} usernameemail {{/str}}
                    {{/canloginbyemail}}
                </label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl group-focus-within:text-primary transition-colors">person</span>
                    <input type="text" name="username" id="username" value="{{username}}" 
                        class="w-full pl-12 pr-4 py-4 bg-surface-container-low border-0 rounded-lg focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all text-on-surface placeholder:text-outline/60 text-sm" 
                        placeholder="{{^canloginbyemail}}{{#cleanstr}}username{{/cleanstr}}{{/canloginbyemail}}{{#canloginbyemail}}{{#cleanstr}}usernameemail{{/cleanstr}}{{/canloginbyemail}}" 
                        autocomplete="username">
                </div>
            </div>
            
            <!-- Password Field -->
            <div class="space-y-2">
                <div class="flex justify-between items-center px-1">
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="password">{{#str}} password {{/str}}</label>
                    <a class="text-xs font-semibold text-primary hover:text-primary-dim transition-colors" href="{{forgotpasswordurl}}">{{#str}}forgotaccount{{/str}}</a>
                </div>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl group-focus-within:text-primary transition-colors">lock</span>
                    <input type="password" name="password" id="password" value="" 
                        class="w-full pl-12 pr-4 py-4 bg-surface-container-low border-0 rounded-lg focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all text-on-surface placeholder:text-outline/60 text-sm" 
                        placeholder="{{#cleanstr}}password{{/cleanstr}}" 
                        autocomplete="current-password">
                </div>
            </div>
            
            {{#recaptcha}}
                <div class="login-form-recaptcha">
                    <div class="flex justify-center">
                        {{{recaptcha}}}
                    </div>
                </div>
            {{/recaptcha}}
            
            <!-- Login Button -->
            <button class="w-full kinetic-gradient text-white font-headline font-bold py-4 px-6 rounded-full hover:shadow-lg hover:shadow-primary/30 transform transition-all active:scale-[0.98] mt-4" type="submit" id="loginbtn">{{#str}}login{{/str}}</button>
        </form>
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <form class="space-y-6" action="';
                $value = $this->resolveValue($context->find('loginurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" method="post" id="login">
';
                $buffer .= $indent . '            <input id="anchor" type="hidden" name="anchor" value="">
';
                $buffer .= $indent . '            <script>document.getElementById(\'anchor\').value = location.hash;</script>
';
                $buffer .= $indent . '            <input type="hidden" name="logintoken" value="';
                $value = $this->resolveValue($context->find('logintoken'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">
';
                $buffer .= $indent . '            
';
                $buffer .= $indent . '            <!-- Username Field -->
';
                $buffer .= $indent . '            <div class="space-y-2">
';
                $buffer .= $indent . '                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1" for="username">
';
                $value = $context->find('canloginbyemail');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                        ';
                    $value = $context->find('str');
                    $buffer .= $this->section27e9419edc620e0e1872d2a6521f1533($context, $indent, $value);
                    $buffer .= '
';
                }
                $value = $context->find('canloginbyemail');
                $buffer .= $this->section95688399ea76ca26a40ecb6df9ae7e86($context, $indent, $value);
                $buffer .= $indent . '                </label>
';
                $buffer .= $indent . '                <div class="relative group">
';
                $buffer .= $indent . '                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl group-focus-within:text-primary transition-colors">person</span>
';
                $buffer .= $indent . '                    <input type="text" name="username" id="username" value="';
                $value = $this->resolveValue($context->find('username'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" 
';
                $buffer .= $indent . '                        class="w-full pl-12 pr-4 py-4 bg-surface-container-low border-0 rounded-lg focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all text-on-surface placeholder:text-outline/60 text-sm" 
';
                $buffer .= $indent . '                        placeholder="';
                $value = $context->find('canloginbyemail');
                if (empty($value)) {
                    
                    $value = $context->find('cleanstr');
                    $buffer .= $this->sectionFea69428308e6a733cfeebf7670bdc01($context, $indent, $value);
                }
                $value = $context->find('canloginbyemail');
                $buffer .= $this->section118ece6c412804f669c845b43ecc9a01($context, $indent, $value);
                $buffer .= '" 
';
                $buffer .= $indent . '                        autocomplete="username">
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '            
';
                $buffer .= $indent . '            <!-- Password Field -->
';
                $buffer .= $indent . '            <div class="space-y-2">
';
                $buffer .= $indent . '                <div class="flex justify-between items-center px-1">
';
                $buffer .= $indent . '                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="password">';
                $value = $context->find('str');
                $buffer .= $this->sectionE056be559d6d01a9bd2bf6f760f8e3e3($context, $indent, $value);
                $buffer .= '</label>
';
                $buffer .= $indent . '                    <a class="text-xs font-semibold text-primary hover:text-primary-dim transition-colors" href="';
                $value = $this->resolveValue($context->find('forgotpasswordurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">';
                $value = $context->find('str');
                $buffer .= $this->sectionE3afea308016df7243ba8871f7081e79($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '                <div class="relative group">
';
                $buffer .= $indent . '                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl group-focus-within:text-primary transition-colors">lock</span>
';
                $buffer .= $indent . '                    <input type="password" name="password" id="password" value="" 
';
                $buffer .= $indent . '                        class="w-full pl-12 pr-4 py-4 bg-surface-container-low border-0 rounded-lg focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all text-on-surface placeholder:text-outline/60 text-sm" 
';
                $buffer .= $indent . '                        placeholder="';
                $value = $context->find('cleanstr');
                $buffer .= $this->section4e50d9b1632f258e8c10be3e2ed759be($context, $indent, $value);
                $buffer .= '" 
';
                $buffer .= $indent . '                        autocomplete="current-password">
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '            
';
                $value = $context->find('recaptcha');
                $buffer .= $this->section62c301b193fb67b798b9581eaed1c246($context, $indent, $value);
                $buffer .= $indent . '            
';
                $buffer .= $indent . '            <!-- Login Button -->
';
                $buffer .= $indent . '            <button class="w-full kinetic-gradient text-white font-headline font-bold py-4 px-6 rounded-full hover:shadow-lg hover:shadow-primary/30 transform transition-all active:scale-[0.98] mt-4" type="submit" id="loginbtn">';
                $value = $context->find('str');
                $buffer .= $this->sectionB15dee8971ab065bf4d6402b60d852be($context, $indent, $value);
                $buffer .= '</button>
';
                $buffer .= $indent . '        </form>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE384f0e9b1fcc321a1a78dba1d43f63f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' potentialidps, auth ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' potentialidps, auth ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section60c83f7b22404177e8062ecd72843cf0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            <img src="{{iconurl}}" alt="" width="24" height="24"/>
                        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                            <img src="';
                $value = $this->resolveValue($context->find('iconurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" alt="" width="24" height="24"/>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section528ae1022190bbe8b38d04e87c912de8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <a class="w-full flex items-center justify-center gap-3 py-3 px-6 bg-surface-container-high hover:bg-surface-container rounded-lg text-on-surface font-medium transition-all" href="{{url}}">
                        {{#iconurl}}
                            <img src="{{iconurl}}" alt="" width="24" height="24"/>
                        {{/iconurl}}
                        {{name}}
                    </a>
                ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                    <a class="w-full flex items-center justify-center gap-3 py-3 px-6 bg-surface-container-high hover:bg-surface-container rounded-lg text-on-surface font-medium transition-all" href="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">
';
                $value = $context->find('iconurl');
                $buffer .= $this->section60c83f7b22404177e8062ecd72843cf0($context, $indent, $value);
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '
';
                $buffer .= $indent . '                    </a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB1695062e04b5e9457492827987e29d1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mt-8 pt-8 border-t border-outline-variant/10">
            <h2 class="font-headline text-lg font-bold text-on-surface mb-4 text-center">{{#str}} potentialidps, auth {{/str}}</h2>
            <div class="space-y-3">
                {{#identityproviders}}
                    <a class="w-full flex items-center justify-center gap-3 py-3 px-6 bg-surface-container-high hover:bg-surface-container rounded-lg text-on-surface font-medium transition-all" href="{{url}}">
                        {{#iconurl}}
                            <img src="{{iconurl}}" alt="" width="24" height="24"/>
                        {{/iconurl}}
                        {{name}}
                    </a>
                {{/identityproviders}}
            </div>
        </div>
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <div class="mt-8 pt-8 border-t border-outline-variant/10">
';
                $buffer .= $indent . '            <h2 class="font-headline text-lg font-bold text-on-surface mb-4 text-center">';
                $value = $context->find('str');
                $buffer .= $this->sectionE384f0e9b1fcc321a1a78dba1d43f63f($context, $indent, $value);
                $buffer .= '</h2>
';
                $buffer .= $indent . '            <div class="space-y-3">
';
                $value = $context->find('identityproviders');
                $buffer .= $this->section528ae1022190bbe8b38d04e87c912de8($context, $indent, $value);
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB681534bda1faeeb31506c30e72ff16e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'firsttime';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'firsttime';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0a033aee2e25356fb18557434886f27b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mt-8 pt-8 border-t border-outline-variant/10">
            <h2 class="font-headline text-lg font-bold text-on-surface mb-3 text-center">{{#str}}firsttime{{/str}}</h2>
            <div class="text-sm text-on-surface-variant text-center mb-4">
                {{{instructions}}}
            </div>
        </div>
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <div class="mt-8 pt-8 border-t border-outline-variant/10">
';
                $buffer .= $indent . '            <h2 class="font-headline text-lg font-bold text-on-surface mb-3 text-center">';
                $value = $context->find('str');
                $buffer .= $this->sectionB681534bda1faeeb31506c30e72ff16e($context, $indent, $value);
                $buffer .= '</h2>
';
                $buffer .= $indent . '            <div class="text-sm text-on-surface-variant text-center mb-4">
';
                $buffer .= $indent . '                ';
                $value = $this->resolveValue($context->find('instructions'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section47f819a53e4575a4e7767be1939ab3bf(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'startsignup';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'startsignup';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2d7b768e8d01bce5cffd21dc45cc8381(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mt-6 text-center">
            <a class="inline-block py-3 px-6 bg-surface-container-high hover:bg-surface-container rounded-lg text-on-surface font-medium transition-all" href="{{signupurl}}">{{#str}}startsignup{{/str}}</a>
        </div>
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <div class="mt-6 text-center">
';
                $buffer .= $indent . '            <a class="inline-block py-3 px-6 bg-surface-container-high hover:bg-surface-container rounded-lg text-on-surface font-medium transition-all" href="';
                $value = $this->resolveValue($context->find('signupurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">';
                $value = $context->find('str');
                $buffer .= $this->section47f819a53e4575a4e7767be1939ab3bf($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section93e4b62aaf677bf7878b06c5ac540671(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'someallowguest';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'someallowguest';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section017c9686023b74877131737c59ff1162(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'loginguest';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'loginguest';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3a290868fb5777d37477438a622763dd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <div class="mt-8 pt-8 border-t border-outline-variant/10">
            <h2 class="font-headline text-lg font-bold text-on-surface mb-4 text-center">{{#str}}someallowguest{{/str}}</h2>
            <form action="{{loginurl}}" method="post" id="guestlogin">
                <input type="hidden" name="logintoken" value="{{logintoken}}">
                <input type="hidden" name="username" value="guest" />
                <input type="hidden" name="password" value="guest" />
                <button class="w-full py-3 px-6 bg-surface-container-high hover:bg-surface-container rounded-lg text-on-surface font-medium transition-all" type="submit" id="loginguestbtn">{{#str}}loginguest{{/str}}</button>
            </form>
        </div>
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        <div class="mt-8 pt-8 border-t border-outline-variant/10">
';
                $buffer .= $indent . '            <h2 class="font-headline text-lg font-bold text-on-surface mb-4 text-center">';
                $value = $context->find('str');
                $buffer .= $this->section93e4b62aaf677bf7878b06c5ac540671($context, $indent, $value);
                $buffer .= '</h2>
';
                $buffer .= $indent . '            <form action="';
                $value = $this->resolveValue($context->find('loginurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" method="post" id="guestlogin">
';
                $buffer .= $indent . '                <input type="hidden" name="logintoken" value="';
                $value = $this->resolveValue($context->find('logintoken'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">
';
                $buffer .= $indent . '                <input type="hidden" name="username" value="guest" />
';
                $buffer .= $indent . '                <input type="hidden" name="password" value="guest" />
';
                $buffer .= $indent . '                <button class="w-full py-3 px-6 bg-surface-container-high hover:bg-surface-container rounded-lg text-on-surface font-medium transition-all" type="submit" id="loginguestbtn">';
                $value = $context->find('str');
                $buffer .= $this->section017c9686023b74877131737c59ff1162($context, $indent, $value);
                $buffer .= '</button>
';
                $buffer .= $indent . '            </form>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4f859d6cb1a86ba6594d0d47cd961df5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                {{#str}}firsttime{{/str}} 
                <a class="text-on-surface font-bold hover:text-primary transition-colors underline underline-offset-4 decoration-primary/30" href="{{signupurl}}">{{#str}}startsignup{{/str}}</a>
            ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                ';
                $value = $context->find('str');
                $buffer .= $this->sectionB681534bda1faeeb31506c30e72ff16e($context, $indent, $value);
                $buffer .= ' 
';
                $buffer .= $indent . '                <a class="text-on-surface font-bold hover:text-primary transition-colors underline underline-offset-4 decoration-primary/30" href="';
                $value = $this->resolveValue($context->find('signupurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">';
                $value = $context->find('str');
                $buffer .= $this->section47f819a53e4575a4e7767be1939ab3bf($context, $indent, $value);
                $buffer .= '</a>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3587dafaae8e35adc97a3429715bae5e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'contactadmin';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'contactadmin';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section43ea3d4a9e87b4be73a9b22bf3a75cd4(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <div class="login-languagemenu">
                    {{>core/action_menu}}
                </div>
            ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                <div class="login-languagemenu">
';
                if ($partial = $this->mustache->loadPartial('core/action_menu')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                    ');
                }
                $buffer .= $indent . '                </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFcb729cc74d31bce5e3746aa60b79a2e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'cookiesnotice';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'cookiesnotice';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFf79f9678cd9b828346cced8c2f3dc21(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            require([\'core_form/events\'], function(FormEvent) {
                function autoFocus() {
                    const userNameField = document.getElementById(\'username\');
                    const passwordField = document.getElementById(\'password\');
                    if (userNameField && userNameField.value.length == 0) {
                        userNameField.focus();
                    } else if (passwordField) {
                        passwordField.focus();
                    }
                }
                autoFocus();
                window.addEventListener(FormEvent.eventTypes.fieldStructureChanged, autoFocus);
            });
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            require([\'core_form/events\'], function(FormEvent) {
';
                $buffer .= $indent . '                function autoFocus() {
';
                $buffer .= $indent . '                    const userNameField = document.getElementById(\'username\');
';
                $buffer .= $indent . '                    const passwordField = document.getElementById(\'password\');
';
                $buffer .= $indent . '                    if (userNameField && userNameField.value.length == 0) {
';
                $buffer .= $indent . '                        userNameField.focus();
';
                $buffer .= $indent . '                    } else if (passwordField) {
';
                $buffer .= $indent . '                        passwordField.focus();
';
                $buffer .= $indent . '                    }
';
                $buffer .= $indent . '                }
';
                $buffer .= $indent . '                autoFocus();
';
                $buffer .= $indent . '                window.addEventListener(FormEvent.eventTypes.fieldStructureChanged, autoFocus);
';
                $buffer .= $indent . '            });
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section86936ee876abb998e79f7d7adb0accc7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        require([\'core/togglesensitive\'], function(ToggleSensitive) {
            ToggleSensitive.init("password", {{smallscreensonly}});
        });
    ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '        require([\'core/togglesensitive\'], function(ToggleSensitive) {
';
                $buffer .= $indent . '            ToggleSensitive.init("password", ';
                $value = $this->resolveValue($context->find('smallscreensonly'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= ');
';
                $buffer .= $indent . '        });
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7246002cb41fa0db9ef447a66544b0c8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            Submit.init("loginguestbtn");
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            Submit.init("loginguestbtn");
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7afc41bda62bae2dd1ad0c9d46705ec8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    {{^error}}
        {{#autofocusform}}
            require([\'core_form/events\'], function(FormEvent) {
                function autoFocus() {
                    const userNameField = document.getElementById(\'username\');
                    const passwordField = document.getElementById(\'password\');
                    if (userNameField && userNameField.value.length == 0) {
                        userNameField.focus();
                    } else if (passwordField) {
                        passwordField.focus();
                    }
                }
                autoFocus();
                window.addEventListener(FormEvent.eventTypes.fieldStructureChanged, autoFocus);
            });
        {{/autofocusform}}
    {{/error}}
    require([\'core/pending\'], function(Pending) {
        const errorMessageDiv = document.getElementById(\'loginerrormessage\');
        const infoMessageDiv = document.getElementById(\'logininfomessage\');
        const errorMessage = errorMessageDiv?.textContent.trim();
        const infoMessage = infoMessageDiv?.textContent.trim();
        if (errorMessage || infoMessage) {
            const pendingJS = new Pending(\'login-move-focus\');
            const usernameField = document.getElementById(\'username\');
            setTimeout(function() {
                // Focus on the username field on error.
                if (errorMessage && usernameField) {
                    usernameField.focus();
                }
                // Append a non-breaking space to the error/status message so screen readers will announce them after page load.
                if (errorMessage) {
                    errorMessageDiv.innerHTML += "&nbsp;";
                }
                if (infoMessage) {
                    infoMessageDiv.innerHTML += "&nbsp;";
                }
                pendingJS.resolve();
            }, 500);
        }
    });
    {{#togglepassword}}
        require([\'core/togglesensitive\'], function(ToggleSensitive) {
            ToggleSensitive.init("password", {{smallscreensonly}});
        });
    {{/togglepassword}}
    require([\'core_form/submit\'], function(Submit) {
        Submit.init("loginbtn");
        {{#canloginasguest}}
            Submit.init("loginguestbtn");
        {{/canloginasguest}}
    });
';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $value = $context->find('error');
                if (empty($value)) {
                    
                    $value = $context->find('autofocusform');
                    $buffer .= $this->sectionFf79f9678cd9b828346cced8c2f3dc21($context, $indent, $value);
                }
                $buffer .= $indent . '    require([\'core/pending\'], function(Pending) {
';
                $buffer .= $indent . '        const errorMessageDiv = document.getElementById(\'loginerrormessage\');
';
                $buffer .= $indent . '        const infoMessageDiv = document.getElementById(\'logininfomessage\');
';
                $buffer .= $indent . '        const errorMessage = errorMessageDiv?.textContent.trim();
';
                $buffer .= $indent . '        const infoMessage = infoMessageDiv?.textContent.trim();
';
                $buffer .= $indent . '        if (errorMessage || infoMessage) {
';
                $buffer .= $indent . '            const pendingJS = new Pending(\'login-move-focus\');
';
                $buffer .= $indent . '            const usernameField = document.getElementById(\'username\');
';
                $buffer .= $indent . '            setTimeout(function() {
';
                $buffer .= $indent . '                // Focus on the username field on error.
';
                $buffer .= $indent . '                if (errorMessage && usernameField) {
';
                $buffer .= $indent . '                    usernameField.focus();
';
                $buffer .= $indent . '                }
';
                $buffer .= $indent . '                // Append a non-breaking space to the error/status message so screen readers will announce them after page load.
';
                $buffer .= $indent . '                if (errorMessage) {
';
                $buffer .= $indent . '                    errorMessageDiv.innerHTML += "&nbsp;";
';
                $buffer .= $indent . '                }
';
                $buffer .= $indent . '                if (infoMessage) {
';
                $buffer .= $indent . '                    infoMessageDiv.innerHTML += "&nbsp;";
';
                $buffer .= $indent . '                }
';
                $buffer .= $indent . '                pendingJS.resolve();
';
                $buffer .= $indent . '            }, 500);
';
                $buffer .= $indent . '        }
';
                $buffer .= $indent . '    });
';
                $value = $context->find('togglepassword');
                $buffer .= $this->section86936ee876abb998e79f7d7adb0accc7($context, $indent, $value);
                $buffer .= $indent . '    require([\'core_form/submit\'], function(Submit) {
';
                $buffer .= $indent . '        Submit.init("loginbtn");
';
                $value = $context->find('canloginasguest');
                $buffer .= $this->section7246002cb41fa0db9ef447a66544b0c8($context, $indent, $value);
                $buffer .= $indent . '    });
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
