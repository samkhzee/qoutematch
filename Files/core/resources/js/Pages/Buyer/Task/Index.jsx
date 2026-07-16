import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';
import TrialTaskList from '@/Components/Shared/TrialTaskList';

export default function Index({ pageTitle, tasks }) {
    return (
        <BuyerMasterLayout pageTitle={pageTitle}>
            <TrialTaskList tasks={tasks} role="buyer" />
        </BuyerMasterLayout>
    );
}
