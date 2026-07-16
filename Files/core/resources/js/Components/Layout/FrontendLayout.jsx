import AppLayout from '@/Components/Layout/AppLayout';
import Header from '@/Components/Partials/Header';
import Footer from '@/Components/Partials/Footer';
import Breadcrumb from '@/Components/Partials/Breadcrumb';

export default function FrontendLayout({
    children,
    pageTitle,
    seo,
    showBreadcrumb = true,
    customPageTitle,
    customSubPageTitle,
    toRoute,
}) {
    return (
        <AppLayout pageTitle={pageTitle} seo={seo}>
            <Header />

            <main>
                {showBreadcrumb && (
                    <Breadcrumb
                        pageTitle={pageTitle}
                        customPageTitle={customPageTitle}
                        customSubPageTitle={customSubPageTitle}
                        toRoute={toRoute}
                    />
                )}
                {children}
            </main>

            <Footer />
        </AppLayout>
    );
}
