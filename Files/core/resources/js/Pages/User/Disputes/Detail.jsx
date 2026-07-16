import MasterLayout from '@/Components/Layout/MasterLayout';
import DisputeDetail from '@/Components/Shared/DisputeDetail';

export default function Detail({ pageTitle, dispute }) {
    return (
        <MasterLayout pageTitle={pageTitle}>
            <DisputeDetail dispute={dispute} />
        </MasterLayout>
    );
}
