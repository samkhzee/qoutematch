import MasterLayout from '@/Components/Layout/MasterLayout';
import ChatInbox from '@/Components/Shared/ChatInbox';

export default function Conversation(props) {
    return (
        <MasterLayout pageTitle={props.pageTitle}>
            <ChatInbox {...props} />
        </MasterLayout>
    );
}
