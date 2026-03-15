<?php

class __Mustache_3bcabafe44d42558e72c7014f6365787 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<h2>';
        $value = $context->find('str');
        $buffer .= $this->section82f7ab41f51044a26097bb045af07dce($context, $indent, $value);
        $buffer .= ' ';
        $value = $context->find('addqbank');
        $buffer .= $this->section23447198967c1b44862212d1b7028be9($context, $indent, $value);
        $buffer .= '</h2>
';
        $buffer .= $indent . '
';
        $value = $context->find('addcustombanks');
        $buffer .= $this->sectionBdeedaaea288261873a4fa0303f243fc($context, $indent, $value);
        $buffer .= $indent . '
';
        $value = $context->find('sharedbanks');
        if (empty($value)) {
            
            $buffer .= $indent . '    <div class="alert alert-info">
';
            $buffer .= $indent . '        ';
            $value = $context->find('str');
            $buffer .= $this->section9a6e74239b2049269ef08035443293f7($context, $indent, $value);
            $buffer .= '
';
            $value = $context->find('createdefault');
            $buffer .= $this->section128f18b905b445d5b8be0ccb6d368e83($context, $indent, $value);
            $buffer .= $indent . '    </div>
';
        }
        $buffer .= $indent . '
';
        $value = $context->find('hassharedbanks');
        $buffer .= $this->sectionB0d651e89051b352ecd4f198789a1359($context, $indent, $value);
        $buffer .= $indent . '
';
        $value = $context->find('hasprivatebanks');
        $buffer .= $this->sectionAc4b53ba76dea6c22ce2deaf1292ce22($context, $indent, $value);

        return $buffer;
    }

    private function section82f7ab41f51044a26097bb045af07dce(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'banksincourse, question';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'banksincourse, question';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section23447198967c1b44862212d1b7028be9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="float-end">{{> core/single_button }}</div>';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= '<div class="float-end">';
                if ($partial = $this->mustache->loadPartial('core/single_button')) {
                    $buffer .= $partial->renderInternal($context);
                }
                $buffer .= '</div>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBdeedaaea288261873a4fa0303f243fc(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="add-bank pt-2">
        <ul class="list-unstyled">
            <li>
                {{> core/action_link}}
            </li>
        </ul>
    </div>
';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '    <div class="add-bank pt-2">
';
                $buffer .= $indent . '        <ul class="list-unstyled">
';
                $buffer .= $indent . '            <li>
';
                if ($partial = $this->mustache->loadPartial('core/action_link')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                ');
                }
                $buffer .= $indent . '            </li>
';
                $buffer .= $indent . '        </ul>
';
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9a6e74239b2049269ef08035443293f7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'nobanks, question';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'nobanks, question';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section128f18b905b445d5b8be0ccb6d368e83(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div>
                {{> core/single_button }}
            </div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div>
';
                if ($partial = $this->mustache->loadPartial('core/single_button')) {
                    $buffer .= $partial->renderInternal($context, $indent . '                ');
                }
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section406ac70e89f5f3abf4dfbfbf0d62f485(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{< core_question/question_banks_list}}
            {{/core_question/question_banks_list}}
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            ';
                if ($parent = $this->mustache->loadPartial('core_question/question_banks_list')) {
                    $buffer .= $parent->renderInternal($context, $indent);
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB0d651e89051b352ecd4f198789a1359(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="shared-banks">
        {{#sharedbanks}}
            {{< core_question/question_banks_list}}
            {{/core_question/question_banks_list}}
        {{/sharedbanks}}
    </div>
';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '    <div class="shared-banks">
';
                $value = $context->find('sharedbanks');
                $buffer .= $this->section406ac70e89f5f3abf4dfbfbf0d62f485($context, $indent, $value);
                $buffer .= '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3468a8278777bfddf032f52d7d0fd380(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'otherbanks, question';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'otherbanks, question';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8bbe00465de32eeb4db5121d16713437(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'otherbanksdesc, question';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'otherbanksdesc, question';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionAc4b53ba76dea6c22ce2deaf1292ce22(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="private-banks pt-5">
        <h2>{{#str}}otherbanks, question{{/str}}</h2>
        <p>{{#str}}otherbanksdesc, question{{/str}}</p>
        {{#privatebanks}}
            {{< core_question/question_banks_list}}
            {{/core_question/question_banks_list}}
        {{/privatebanks}}
    </div>
';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '    <div class="private-banks pt-5">
';
                $buffer .= $indent . '        <h2>';
                $value = $context->find('str');
                $buffer .= $this->section3468a8278777bfddf032f52d7d0fd380($context, $indent, $value);
                $buffer .= '</h2>
';
                $buffer .= $indent . '        <p>';
                $value = $context->find('str');
                $buffer .= $this->section8bbe00465de32eeb4db5121d16713437($context, $indent, $value);
                $buffer .= '</p>
';
                $value = $context->find('privatebanks');
                $buffer .= $this->section406ac70e89f5f3abf4dfbfbf0d62f485($context, $indent, $value);
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
