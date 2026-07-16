import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';
import TrialTaskDetail from '@/Components/Shared/TrialTaskDetail';

export default function Detail({ pageTitle, task }) {
    return (
        <BuyerMasterLayout pageTitle={pageTitle}>
            <TrialTaskDetail task={task} />
        </BuyerMasterLayout>
    );
}
