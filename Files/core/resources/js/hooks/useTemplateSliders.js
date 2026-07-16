import { useEffect, useRef } from 'react';
import { initTemplateSliders, destroyTemplateSliders, slidersNeedInit } from '@/utils/sliders';

export function useTemplateSliders(deps = []) {
    const timerRef = useRef(null);

    useEffect(() => {
        let attempts = 0;
        const maxAttempts = 40;

        const run = () => {
            if (!window.jQuery?.fn?.slick) {
                if (attempts++ < maxAttempts) {
                    timerRef.current = window.setTimeout(run, 100);
                }
                return;
            }

            initTemplateSliders();

            if (slidersNeedInit() && attempts++ < maxAttempts) {
                timerRef.current = window.setTimeout(run, 150);
            }
        };

        timerRef.current = window.setTimeout(run, 100);

        return () => {
            if (timerRef.current) {
                window.clearTimeout(timerRef.current);
            }
            destroyTemplateSliders();
        };
    }, deps);
}
