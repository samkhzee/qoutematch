import FrontendLayout from '@/Components/Layout/FrontendLayout';
import SectionRenderer, { Banner } from '@/Components/Sections/SectionRenderer';
import { useTemplateSliders } from '@/hooks/useTemplateSliders';

export default function Home({ pageTitle, seo, sections, banner }) {
    useTemplateSliders([sections]);

    return (
        <FrontendLayout pageTitle={pageTitle} seo={seo} showBreadcrumb={false}>
            <Banner data={banner} />
            <SectionRenderer sections={sections} />
        </FrontendLayout>
    );
}
