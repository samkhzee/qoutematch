import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';
import TrialTaskForm from '@/Components/Shared/TrialTaskForm';

export default function Form({ pageTitle, formData }) {
    return (
        <BuyerMasterLayout pageTitle={pageTitle}>
            <TrialTaskForm formData={formData} />
        </BuyerMasterLayout>
    );
}
