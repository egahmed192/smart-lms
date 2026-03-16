<?php

class __Mustache_e85759399df45a6528eb129d72e397ec extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<style>
';
        $buffer .= $indent . '/* Modern Login Page Styles */
';
        $buffer .= $indent . '.modern-login-wrapper {
';
        $buffer .= $indent . '    min-height: 100vh;
';
        $buffer .= $indent . '    display: flex;
';
        $buffer .= $indent . '    align-items: center;
';
        $buffer .= $indent . '    justify-content: center;
';
        $buffer .= $indent . '    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
';
        $buffer .= $indent . '    padding: 20px;
';
        $buffer .= $indent . '    position: relative;
';
        $buffer .= $indent . '    overflow: hidden;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-login-wrapper::before {
';
        $buffer .= $indent . '    content: \'\';
';
        $buffer .= $indent . '    position: absolute;
';
        $buffer .= $indent . '    width: 200%;
';
        $buffer .= $indent . '    height: 200%;
';
        $buffer .= $indent . '    background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
';
        $buffer .= $indent . '    background-size: 50px 50px;
';
        $buffer .= $indent . '    animation: moveBackground 20s linear infinite;
';
        $buffer .= $indent . '    opacity: 0.3;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '@keyframes moveBackground {
';
        $buffer .= $indent . '    0% { transform: translate(0, 0); }
';
        $buffer .= $indent . '    100% { transform: translate(50px, 50px); }
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-login-card {
';
        $buffer .= $indent . '    background: rgba(255, 255, 255, 0.95);
';
        $buffer .= $indent . '    backdrop-filter: blur(10px);
';
        $buffer .= $indent . '    border-radius: 24px;
';
        $buffer .= $indent . '    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
';
        $buffer .= $indent . '    padding: 48px 40px;
';
        $buffer .= $indent . '    width: 100%;
';
        $buffer .= $indent . '    max-width: 440px;
';
        $buffer .= $indent . '    position: relative;
';
        $buffer .= $indent . '    z-index: 1;
';
        $buffer .= $indent . '    animation: slideUp 0.6s ease-out;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '@keyframes slideUp {
';
        $buffer .= $indent . '    from {
';
        $buffer .= $indent . '        opacity: 0;
';
        $buffer .= $indent . '        transform: translateY(30px);
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '    to {
';
        $buffer .= $indent . '        opacity: 1;
';
        $buffer .= $indent . '        transform: translateY(0);
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-login-header {
';
        $buffer .= $indent . '    text-align: center;
';
        $buffer .= $indent . '    margin-bottom: 36px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-login-header h1 {
';
        $buffer .= $indent . '    font-size: 32px;
';
        $buffer .= $indent . '    font-weight: 700;
';
        $buffer .= $indent . '    color: #2d3748;
';
        $buffer .= $indent . '    margin: 0 0 8px 0;
';
        $buffer .= $indent . '    letter-spacing: -0.5px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-login-header .subtitle {
';
        $buffer .= $indent . '    color: #718096;
';
        $buffer .= $indent . '    font-size: 15px;
';
        $buffer .= $indent . '    margin-top: 8px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-login-logo {
';
        $buffer .= $indent . '    margin-bottom: 24px;
';
        $buffer .= $indent . '    text-align: center;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-login-logo img {
';
        $buffer .= $indent . '    max-height: 60px;
';
        $buffer .= $indent . '    width: auto;
';
        $buffer .= $indent . '    border-radius: 8px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-alert {
';
        $buffer .= $indent . '    padding: 14px 18px;
';
        $buffer .= $indent . '    border-radius: 12px;
';
        $buffer .= $indent . '    margin-bottom: 24px;
';
        $buffer .= $indent . '    font-size: 14px;
';
        $buffer .= $indent . '    line-height: 1.5;
';
        $buffer .= $indent . '    animation: shake 0.5s;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '@keyframes shake {
';
        $buffer .= $indent . '    0%, 100% { transform: translateX(0); }
';
        $buffer .= $indent . '    25% { transform: translateX(-10px); }
';
        $buffer .= $indent . '    75% { transform: translateX(10px); }
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-alert-danger {
';
        $buffer .= $indent . '    background: #fee;
';
        $buffer .= $indent . '    border: 1px solid #fcc;
';
        $buffer .= $indent . '    color: #c33;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-alert-info {
';
        $buffer .= $indent . '    background: #e6f3ff;
';
        $buffer .= $indent . '    border: 1px solid #b3d9ff;
';
        $buffer .= $indent . '    color: #0066cc;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-form-group {
';
        $buffer .= $indent . '    position: relative;
';
        $buffer .= $indent . '    margin-bottom: 24px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-input-wrapper {
';
        $buffer .= $indent . '    position: relative;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-input-icon {
';
        $buffer .= $indent . '    position: absolute;
';
        $buffer .= $indent . '    left: 16px;
';
        $buffer .= $indent . '    top: 50%;
';
        $buffer .= $indent . '    transform: translateY(-50%);
';
        $buffer .= $indent . '    color: #a0aec0;
';
        $buffer .= $indent . '    font-size: 18px;
';
        $buffer .= $indent . '    z-index: 2;
';
        $buffer .= $indent . '    pointer-events: none;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-input {
';
        $buffer .= $indent . '    width: 100%;
';
        $buffer .= $indent . '    padding: 16px 16px 16px 48px;
';
        $buffer .= $indent . '    font-size: 15px;
';
        $buffer .= $indent . '    border: 2px solid #e2e8f0;
';
        $buffer .= $indent . '    border-radius: 12px;
';
        $buffer .= $indent . '    background: #fff;
';
        $buffer .= $indent . '    transition: all 0.3s ease;
';
        $buffer .= $indent . '    color: #2d3748;
';
        $buffer .= $indent . '    box-sizing: border-box;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-input:focus {
';
        $buffer .= $indent . '    outline: none;
';
        $buffer .= $indent . '    border-color: #667eea;
';
        $buffer .= $indent . '    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
';
        $buffer .= $indent . '    transform: translateY(-2px);
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-input::placeholder {
';
        $buffer .= $indent . '    color: #a0aec0;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-btn-primary {
';
        $buffer .= $indent . '    width: 100%;
';
        $buffer .= $indent . '    padding: 16px;
';
        $buffer .= $indent . '    font-size: 16px;
';
        $buffer .= $indent . '    font-weight: 600;
';
        $buffer .= $indent . '    color: #fff;
';
        $buffer .= $indent . '    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
';
        $buffer .= $indent . '    border: none;
';
        $buffer .= $indent . '    border-radius: 12px;
';
        $buffer .= $indent . '    cursor: pointer;
';
        $buffer .= $indent . '    transition: all 0.3s ease;
';
        $buffer .= $indent . '    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
';
        $buffer .= $indent . '    margin-top: 8px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-btn-primary:hover {
';
        $buffer .= $indent . '    transform: translateY(-2px);
';
        $buffer .= $indent . '    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-btn-primary:active {
';
        $buffer .= $indent . '    transform: translateY(0);
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-btn-secondary {
';
        $buffer .= $indent . '    padding: 12px 24px;
';
        $buffer .= $indent . '    font-size: 14px;
';
        $buffer .= $indent . '    font-weight: 500;
';
        $buffer .= $indent . '    color: #667eea;
';
        $buffer .= $indent . '    background: #f7fafc;
';
        $buffer .= $indent . '    border: 2px solid #e2e8f0;
';
        $buffer .= $indent . '    border-radius: 10px;
';
        $buffer .= $indent . '    cursor: pointer;
';
        $buffer .= $indent . '    transition: all 0.3s ease;
';
        $buffer .= $indent . '    text-decoration: none;
';
        $buffer .= $indent . '    display: inline-block;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-btn-secondary:hover {
';
        $buffer .= $indent . '    background: #edf2f7;
';
        $buffer .= $indent . '    border-color: #667eea;
';
        $buffer .= $indent . '    transform: translateY(-1px);
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-forgot-link {
';
        $buffer .= $indent . '    text-align: center;
';
        $buffer .= $indent . '    margin-top: 20px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-forgot-link a {
';
        $buffer .= $indent . '    color: #667eea;
';
        $buffer .= $indent . '    text-decoration: none;
';
        $buffer .= $indent . '    font-size: 14px;
';
        $buffer .= $indent . '    font-weight: 500;
';
        $buffer .= $indent . '    transition: color 0.3s ease;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-forgot-link a:hover {
';
        $buffer .= $indent . '    color: #764ba2;
';
        $buffer .= $indent . '    text-decoration: underline;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-divider {
';
        $buffer .= $indent . '    display: flex;
';
        $buffer .= $indent . '    align-items: center;
';
        $buffer .= $indent . '    margin: 32px 0;
';
        $buffer .= $indent . '    color: #a0aec0;
';
        $buffer .= $indent . '    font-size: 14px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-divider::before,
';
        $buffer .= $indent . '.modern-divider::after {
';
        $buffer .= $indent . '    content: \'\';
';
        $buffer .= $indent . '    flex: 1;
';
        $buffer .= $indent . '    height: 1px;
';
        $buffer .= $indent . '    background: #e2e8f0;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-divider span {
';
        $buffer .= $indent . '    padding: 0 16px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-identity-providers {
';
        $buffer .= $indent . '    margin-top: 24px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-identity-providers h2 {
';
        $buffer .= $indent . '    font-size: 18px;
';
        $buffer .= $indent . '    font-weight: 600;
';
        $buffer .= $indent . '    color: #2d3748;
';
        $buffer .= $indent . '    margin-bottom: 16px;
';
        $buffer .= $indent . '    text-align: center;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-idp-btn {
';
        $buffer .= $indent . '    display: flex;
';
        $buffer .= $indent . '    align-items: center;
';
        $buffer .= $indent . '    justify-content: center;
';
        $buffer .= $indent . '    gap: 12px;
';
        $buffer .= $indent . '    width: 100%;
';
        $buffer .= $indent . '    padding: 14px;
';
        $buffer .= $indent . '    margin-bottom: 12px;
';
        $buffer .= $indent . '    background: #fff;
';
        $buffer .= $indent . '    border: 2px solid #e2e8f0;
';
        $buffer .= $indent . '    border-radius: 12px;
';
        $buffer .= $indent . '    color: #2d3748;
';
        $buffer .= $indent . '    text-decoration: none;
';
        $buffer .= $indent . '    font-weight: 500;
';
        $buffer .= $indent . '    transition: all 0.3s ease;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-idp-btn:hover {
';
        $buffer .= $indent . '    border-color: #667eea;
';
        $buffer .= $indent . '    background: #f7fafc;
';
        $buffer .= $indent . '    transform: translateY(-2px);
';
        $buffer .= $indent . '    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-instructions {
';
        $buffer .= $indent . '    background: #f7fafc;
';
        $buffer .= $indent . '    padding: 20px;
';
        $buffer .= $indent . '    border-radius: 12px;
';
        $buffer .= $indent . '    margin: 24px 0;
';
        $buffer .= $indent . '    color: #4a5568;
';
        $buffer .= $indent . '    font-size: 14px;
';
        $buffer .= $indent . '    line-height: 1.6;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-instructions h2 {
';
        $buffer .= $indent . '    font-size: 18px;
';
        $buffer .= $indent . '    font-weight: 600;
';
        $buffer .= $indent . '    color: #2d3748;
';
        $buffer .= $indent . '    margin-bottom: 12px;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-footer-links {
';
        $buffer .= $indent . '    display: flex;
';
        $buffer .= $indent . '    justify-content: center;
';
        $buffer .= $indent . '    align-items: center;
';
        $buffer .= $indent . '    gap: 16px;
';
        $buffer .= $indent . '    margin-top: 32px;
';
        $buffer .= $indent . '    padding-top: 24px;
';
        $buffer .= $indent . '    border-top: 1px solid #e2e8f0;
';
        $buffer .= $indent . '    flex-wrap: wrap;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-footer-links button,
';
        $buffer .= $indent . '.modern-footer-links a {
';
        $buffer .= $indent . '    color: #718096;
';
        $buffer .= $indent . '    font-size: 13px;
';
        $buffer .= $indent . '    text-decoration: none;
';
        $buffer .= $indent . '    background: none;
';
        $buffer .= $indent . '    border: none;
';
        $buffer .= $indent . '    cursor: pointer;
';
        $buffer .= $indent . '    padding: 8px 12px;
';
        $buffer .= $indent . '    border-radius: 8px;
';
        $buffer .= $indent . '    transition: all 0.3s ease;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '.modern-footer-links button:hover,
';
        $buffer .= $indent . '.modern-footer-links a:hover {
';
        $buffer .= $indent . '    background: #f7fafc;
';
        $buffer .= $indent . '    color: #2d3748;
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '@media (max-width: 480px) {
';
        $buffer .= $indent . '    .modern-login-card {
';
        $buffer .= $indent . '        padding: 32px 24px;
';
        $buffer .= $indent . '        border-radius: 20px;
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    .modern-login-header h1 {
';
        $buffer .= $indent . '        font-size: 26px;
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    .modern-input {
';
        $buffer .= $indent . '        padding: 14px 14px 14px 44px;
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '}
';
        $buffer .= $indent . '</style>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="modern-login-wrapper">
';
        $buffer .= $indent . '    <div class="modern-login-card">
';
        $buffer .= $indent . '        <div class="modern-login-header">
';
        $value = $context->find('logourl');
        $buffer .= $this->sectionAcf7aad0a36bab28a3a1a745f60e0b4d($context, $indent, $value);
        $buffer .= $indent . '            <h1>';
        $value = $context->find('str');
        $buffer .= $this->section80a4d1690ff171d23b2097c3acda60e4($context, $indent, $value);
        $buffer .= '</h1>
';
        $buffer .= $indent . '            <p class="subtitle">Welcome back! Please login to continue.</p>
';
        $buffer .= $indent . '        </div>
';
        $value = $context->find('maintenance');
        $buffer .= $this->sectionE85af244d1ac64a7030d6199b101b9e7($context, $indent, $value);
        $value = $context->find('error');
        $buffer .= $this->section34efd4d3c0bfd4425cceb876e7ebd89b($context, $indent, $value);
        $value = $context->find('info');
        $buffer .= $this->sectionD2b0571a89ca0ad7769da144e2408921($context, $indent, $value);
        $value = $context->find('showloginform');
        $buffer .= $this->sectionA86aa3e948036369d664c04b2f823dce($context, $indent, $value);
        $value = $context->find('hasidentityproviders');
        $buffer .= $this->section021b09340efcc41a92f181d4e2e5ea77($context, $indent, $value);
        $value = $context->find('hasinstructions');
        $buffer .= $this->sectionFd98dcbd0228f1869b9775687d7e57e9($context, $indent, $value);
        $value = $context->find('cansignup');
        $buffer .= $this->sectionA73ad7c2ac71942027e2b81c48334ee1($context, $indent, $value);
        $value = $context->find('canloginasguest');
        $buffer .= $this->section5d7aa4541991149531956f71a4b9ba29($context, $indent, $value);
        $buffer .= $indent . '        <div class="modern-footer-links">
';
        $value = $context->find('languagemenu');
        $buffer .= $this->section43ea3d4a9e87b4be73a9b22bf3a75cd4($context, $indent, $value);
        $buffer .= $indent . '            <button type="button" class="modern-btn-secondary" style="background: transparent; border: none; padding: 8px 12px;" ';
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

    private function sectionAcf7aad0a36bab28a3a1a745f60e0b4d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <div class="modern-login-logo">
                    <img id="logoimage" src="{{logourl}}" alt="{{sitename}}"/>
                </div>
            ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                <div class="modern-login-logo">
';
                $buffer .= $indent . '                    <img id="logoimage" src="';
                $value = $this->resolveValue($context->find('logourl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" alt="';
                $value = $this->resolveValue($context->find('sitename'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '"/>
';
                $buffer .= $indent . '                </div>
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

    private function sectionE85af244d1ac64a7030d6199b101b9e7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="modern-alert modern-alert-danger">
                {{{maintenance}}}
            </div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div class="modern-alert modern-alert-danger">
';
                $buffer .= $indent . '                ';
                $value = $this->resolveValue($context->find('maintenance'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '
';
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section34efd4d3c0bfd4425cceb876e7ebd89b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="modern-alert modern-alert-danger" id="loginerrormessage" role="alert">{{error}}</div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div class="modern-alert modern-alert-danger" id="loginerrormessage" role="alert">';
                $value = $this->resolveValue($context->find('error'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD2b0571a89ca0ad7769da144e2408921(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="modern-alert modern-alert-info" id="logininfomessage" role="status">{{info}}</div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div class="modern-alert modern-alert-info" id="logininfomessage" role="status">';
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

    private function section8933a26f9df590ac6fbaf8286154bdf3(Mustache_Context $context, $indent, $value)
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
                
                $buffer .= $indent . '                            ';
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

    private function sectionBfebfb1dff6d215057021f13376affae(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <div class="modern-form-group">
                        {{{recaptcha}}}
                    </div>
                ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                    <div class="modern-form-group">
';
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->find('recaptcha'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '
';
                $buffer .= $indent . '                    </div>
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

    private function sectionA86aa3e948036369d664c04b2f823dce(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <form class="login-form" action="{{loginurl}}" method="post" id="login">
                <input id="anchor" type="hidden" name="anchor" value="">
                <script>document.getElementById(\'anchor\').value = location.hash;</script>
                <input type="hidden" name="logintoken" value="{{logintoken}}">
                <div class="modern-form-group">
                    <label for="username" class="visually-hidden">
                        {{^canloginbyemail}}
                            {{#str}} username {{/str}}
                        {{/canloginbyemail}}
                        {{#canloginbyemail}}
                            {{#str}} usernameemail {{/str}}
                        {{/canloginbyemail}}
                    </label>
                    <div class="modern-input-wrapper">
                        <span class="modern-input-icon">👤</span>
                        <input type="text" name="username" id="username" class="modern-input" value="{{username}}" placeholder="{{^canloginbyemail}}{{#cleanstr}}username{{/cleanstr}}{{/canloginbyemail}}{{#canloginbyemail}}{{#cleanstr}}usernameemail{{/cleanstr}}{{/canloginbyemail}}" autocomplete="username">
                    </div>
                </div>
                <div class="modern-form-group">
                    <label for="password" class="visually-hidden">{{#str}} password {{/str}}</label>
                    <div class="modern-input-wrapper">
                        <span class="modern-input-icon">🔒</span>
                        <input type="password" name="password" id="password" value="" class="modern-input" placeholder="{{#cleanstr}}password{{/cleanstr}}" autocomplete="current-password">
                    </div>
                </div>
                {{#recaptcha}}
                    <div class="modern-form-group">
                        {{{recaptcha}}}
                    </div>
                {{/recaptcha}}
                <button class="modern-btn-primary" type="submit" id="loginbtn">{{#str}}login{{/str}}</button>
                <div class="modern-forgot-link">
                    <a href="{{forgotpasswordurl}}">{{#str}}forgotaccount{{/str}}</a>
                </div>
            </form>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <form class="login-form" action="';
                $value = $this->resolveValue($context->find('loginurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" method="post" id="login">
';
                $buffer .= $indent . '                <input id="anchor" type="hidden" name="anchor" value="">
';
                $buffer .= $indent . '                <script>document.getElementById(\'anchor\').value = location.hash;</script>
';
                $buffer .= $indent . '                <input type="hidden" name="logintoken" value="';
                $value = $this->resolveValue($context->find('logintoken'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">
';
                $buffer .= $indent . '                <div class="modern-form-group">
';
                $buffer .= $indent . '                    <label for="username" class="visually-hidden">
';
                $value = $context->find('canloginbyemail');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                            ';
                    $value = $context->find('str');
                    $buffer .= $this->section27e9419edc620e0e1872d2a6521f1533($context, $indent, $value);
                    $buffer .= '
';
                }
                $value = $context->find('canloginbyemail');
                $buffer .= $this->section8933a26f9df590ac6fbaf8286154bdf3($context, $indent, $value);
                $buffer .= $indent . '                    </label>
';
                $buffer .= $indent . '                    <div class="modern-input-wrapper">
';
                $buffer .= $indent . '                        <span class="modern-input-icon">👤</span>
';
                $buffer .= $indent . '                        <input type="text" name="username" id="username" class="modern-input" value="';
                $value = $this->resolveValue($context->find('username'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '" placeholder="';
                $value = $context->find('canloginbyemail');
                if (empty($value)) {
                    
                    $value = $context->find('cleanstr');
                    $buffer .= $this->sectionFea69428308e6a733cfeebf7670bdc01($context, $indent, $value);
                }
                $value = $context->find('canloginbyemail');
                $buffer .= $this->section118ece6c412804f669c845b43ecc9a01($context, $indent, $value);
                $buffer .= '" autocomplete="username">
';
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '                <div class="modern-form-group">
';
                $buffer .= $indent . '                    <label for="password" class="visually-hidden">';
                $value = $context->find('str');
                $buffer .= $this->sectionE056be559d6d01a9bd2bf6f760f8e3e3($context, $indent, $value);
                $buffer .= '</label>
';
                $buffer .= $indent . '                    <div class="modern-input-wrapper">
';
                $buffer .= $indent . '                        <span class="modern-input-icon">🔒</span>
';
                $buffer .= $indent . '                        <input type="password" name="password" id="password" value="" class="modern-input" placeholder="';
                $value = $context->find('cleanstr');
                $buffer .= $this->section4e50d9b1632f258e8c10be3e2ed759be($context, $indent, $value);
                $buffer .= '" autocomplete="current-password">
';
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '                </div>
';
                $value = $context->find('recaptcha');
                $buffer .= $this->sectionBfebfb1dff6d215057021f13376affae($context, $indent, $value);
                $buffer .= $indent . '                <button class="modern-btn-primary" type="submit" id="loginbtn">';
                $value = $context->find('str');
                $buffer .= $this->sectionB15dee8971ab065bf4d6402b60d852be($context, $indent, $value);
                $buffer .= '</button>
';
                $buffer .= $indent . '                <div class="modern-forgot-link">
';
                $buffer .= $indent . '                    <a href="';
                $value = $this->resolveValue($context->find('forgotpasswordurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">';
                $value = $context->find('str');
                $buffer .= $this->sectionE3afea308016df7243ba8871f7081e79($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </form>
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

    private function sectionF7ff5acda37fb8f3e37ee0115a9ecfc5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <a class="modern-idp-btn" href="{{url}}">
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
                
                $buffer .= $indent . '                    <a class="modern-idp-btn" href="';
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

    private function section021b09340efcc41a92f181d4e2e5ea77(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="modern-divider"><span>{{#str}} potentialidps, auth {{/str}}</span></div>
            <div class="modern-identity-providers">
                {{#identityproviders}}
                    <a class="modern-idp-btn" href="{{url}}">
                        {{#iconurl}}
                            <img src="{{iconurl}}" alt="" width="24" height="24"/>
                        {{/iconurl}}
                        {{name}}
                    </a>
                {{/identityproviders}}
            </div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div class="modern-divider"><span>';
                $value = $context->find('str');
                $buffer .= $this->sectionE384f0e9b1fcc321a1a78dba1d43f63f($context, $indent, $value);
                $buffer .= '</span></div>
';
                $buffer .= $indent . '            <div class="modern-identity-providers">
';
                $value = $context->find('identityproviders');
                $buffer .= $this->sectionF7ff5acda37fb8f3e37ee0115a9ecfc5($context, $indent, $value);
                $buffer .= $indent . '            </div>
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

    private function sectionFd98dcbd0228f1869b9775687d7e57e9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="modern-divider"><span>{{#str}}firsttime{{/str}}</span></div>
            <div class="modern-instructions">
                <h2>{{#str}}firsttime{{/str}}</h2>
                <div>{{{instructions}}}</div>
            </div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div class="modern-divider"><span>';
                $value = $context->find('str');
                $buffer .= $this->sectionB681534bda1faeeb31506c30e72ff16e($context, $indent, $value);
                $buffer .= '</span></div>
';
                $buffer .= $indent . '            <div class="modern-instructions">
';
                $buffer .= $indent . '                <h2>';
                $value = $context->find('str');
                $buffer .= $this->sectionB681534bda1faeeb31506c30e72ff16e($context, $indent, $value);
                $buffer .= '</h2>
';
                $buffer .= $indent . '                <div>';
                $value = $this->resolveValue($context->find('instructions'), $context);
                $buffer .= ($value === null ? '' : $value);
                $buffer .= '</div>
';
                $buffer .= $indent . '            </div>
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

    private function sectionA73ad7c2ac71942027e2b81c48334ee1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div style="text-align: center; margin-top: 24px;">
                <a class="modern-btn-secondary" href="{{signupurl}}">{{#str}}startsignup{{/str}}</a>
            </div>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div style="text-align: center; margin-top: 24px;">
';
                $buffer .= $indent . '                <a class="modern-btn-secondary" href="';
                $value = $this->resolveValue($context->find('signupurl'), $context);
                $buffer .= ($value === null ? '' : call_user_func($this->mustache->getEscape(), $value));
                $buffer .= '">';
                $value = $context->find('str');
                $buffer .= $this->section47f819a53e4575a4e7767be1939ab3bf($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '            </div>
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

    private function section5d7aa4541991149531956f71a4b9ba29(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <div class="modern-divider"><span>{{#str}}someallowguest{{/str}}</span></div>
            <form action="{{loginurl}}" method="post" id="guestlogin">
                <input type="hidden" name="logintoken" value="{{logintoken}}">
                <input type="hidden" name="username" value="guest" />
                <input type="hidden" name="password" value="guest" />
                <button class="modern-btn-secondary" type="submit" id="loginguestbtn" style="width: 100%;">{{#str}}loginguest{{/str}}</button>
            </form>
        ';
            $result = (string) call_user_func($value, $source, $this->lambdaHelper);
            $buffer .= $result;
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '            <div class="modern-divider"><span>';
                $value = $context->find('str');
                $buffer .= $this->section93e4b62aaf677bf7878b06c5ac540671($context, $indent, $value);
                $buffer .= '</span></div>
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
                $buffer .= $indent . '                <button class="modern-btn-secondary" type="submit" id="loginguestbtn" style="width: 100%;">';
                $value = $context->find('str');
                $buffer .= $this->section017c9686023b74877131737c59ff1162($context, $indent, $value);
                $buffer .= '</button>
';
                $buffer .= $indent . '            </form>
';
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
