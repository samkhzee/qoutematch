import { useEffect, useRef } from 'react';
import { Head } from '@inertiajs/react';
import FrontendLayout from '@/Components/Layout/FrontendLayout';
import AuthLayout from '@/Components/Layout/AuthLayout';
import AppLayout from '@/Components/Layout/AppLayout';
import { initTemplateInteractions } from '@/utils/templateInteractions';

function loadScriptOnce(src) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src="${src}"]`)) {
            resolve();
            return;
        }

        const script = document.createElement('script');
        script.src = src;
        script.onload = () => resolve();
        script.onerror = () => reject(new Error(`Failed to load ${src}`));
        document.body.appendChild(script);
    });
}

function executeScripts(container) {
    if (!container) return;

    container.querySelectorAll('script').forEach((oldScript) => {
        const script = document.createElement('script');
        [...oldScript.attributes].forEach((attr) => script.setAttribute(attr.name, attr.value));
        script.text = oldScript.textContent;
        oldScript.parentNode.replaceChild(script, oldScript);
    });
}

function waitForBridgeDependencies() {
    const jquerySrc = '/assets/global/js/jquery-3.7.1.min.js';

    if (window.jQuery) {
        return Promise.resolve();
    }

    return loadScriptOnce(jquerySrc);
}

function HtmlContent({ html }) {
    const ref = useRef(null);

    useEffect(() => {
        let cancelled = false;

        waitForBridgeDependencies()
            .then(() => {
                if (cancelled) return;
                executeScripts(ref.current);
                initTemplateInteractions();
            })
            .catch(() => {
                if (cancelled) return;
                executeScripts(ref.current);
                initTemplateInteractions();
            });

        return () => {
            cancelled = true;
        };
    }, [html]);

    return <div ref={ref} dangerouslySetInnerHTML={{ __html: html }} />;
}

export default function BridgePage({ layout, html, pageTitle, seo }) {
    const content = <HtmlContent html={html} />;

    const wrapped = (() => {
        switch (layout) {
            case 'frontend':
                return (
                    <FrontendLayout pageTitle={pageTitle} seo={seo} showBreadcrumb={false}>
                        {content}
                    </FrontendLayout>
                );
            case 'master':
            case 'buyer':
            case 'admin':
                return (
                    <AppLayout pageTitle={pageTitle} showPreloader={false}>
                        {content}
                    </AppLayout>
                );
            case 'auth':
                return <AuthLayout pageTitle={pageTitle}>{content}</AuthLayout>;
            case 'bare':
                return (
                    <AppLayout pageTitle={pageTitle} showPreloader={false}>
                        {content}
                    </AppLayout>
                );
            default:
                return content;
        }
    })();

    return (
        <>
            {pageTitle && <Head title={pageTitle} />}
            {wrapped}
        </>
    );
}
