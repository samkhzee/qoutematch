import FrontendLayout from '@/Components/Layout/FrontendLayout';
import SectionRenderer from '@/Components/Sections/SectionRenderer';
import { useTemplateSliders } from '@/hooks/useTemplateSliders';

export default function CmsPage({ pageTitle, seo, sections }) {
    useTemplateSliders([sections]);

    return (
        <FrontendLayout pageTitle={pageTitle} seo={seo}>
            <SectionRenderer sections={sections} />
        </FrontendLayout>
    );
}
