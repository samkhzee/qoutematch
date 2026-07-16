import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';
import DisputeDetail from '@/Components/Shared/DisputeDetail';

export default function Detail({ pageTitle, dispute }) {
    return (
        <BuyerMasterLayout pageTitle={pageTitle}>
            <DisputeDetail dispute={dispute} />
        </BuyerMasterLayout>
    );
}
