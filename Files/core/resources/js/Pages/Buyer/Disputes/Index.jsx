import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';
import DisputeList from '@/Components/Shared/DisputeList';

export default function Index({ pageTitle, disputes }) {
    return (
        <BuyerMasterLayout pageTitle={pageTitle}>
            <DisputeList
                disputes={disputes}
                emptyMessage="No disputes yet. You can open a dispute by reporting a project from My Projects while it is under review."
            />
        </BuyerMasterLayout>
    );
}
