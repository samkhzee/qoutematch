import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';
import ChatInbox from '@/Components/Shared/ChatInbox';

export default function Conversation(props) {
    return (
        <BuyerMasterLayout pageTitle={props.pageTitle}>
            <ChatInbox {...props} />
        </BuyerMasterLayout>
    );
}
