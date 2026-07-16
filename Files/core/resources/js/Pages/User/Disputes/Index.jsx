import MasterLayout from '@/Components/Layout/MasterLayout';
import DisputeList from '@/Components/Shared/DisputeList';

export default function Index({ pageTitle, disputes }) {
    return (
        <MasterLayout pageTitle={pageTitle}>
            <DisputeList
                disputes={disputes}
                emptyMessage="No disputes yet. Disputes are created when you or a customer reports a project."
            />
        </MasterLayout>
    );
}
