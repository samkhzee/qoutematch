import MasterLayout from '@/Components/Layout/MasterLayout';
import TrialTaskList from '@/Components/Shared/TrialTaskList';

export default function Index({ pageTitle, tasks }) {
    return (
        <MasterLayout pageTitle={pageTitle}>
            <TrialTaskList tasks={tasks} role="freelancer" />
        </MasterLayout>
    );
}
