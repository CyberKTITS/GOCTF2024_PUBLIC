import { Address, toNano } from '@ton/core';
import { TaskContract } from '../wrappers/TaskContract';
import { NetworkProvider, sleep } from '@ton/blueprint';

export async function run(provider: NetworkProvider, args: string[]) {
    const ui = provider.ui();

    const address = Address.parse(args.length > 0 ? args[0] : await ui.input('TaskContract address'));
    const seqno = BigInt(args.length > 1 ? args[1] : await ui.input('Seqno'));
    const key = BigInt(args.length > 2 ? args[2] : await ui.input('Key'));

    if (!(await provider.isContractDeployed(address))) {
        ui.write(`Error: Contract at address ${address} is not deployed!`);
        return;
    }

    const taskContract = provider.open(TaskContract.fromAddress(address));

    const seqnoBefore = await taskContract.getSeqno();

    await taskContract.send(
        provider.sender(),
        {
            value: toNano('0.2'),
        },
        {
            $$type: 'CheckKey',
            guessedKey: key,
            seqno: seqno,
        }
    );
    
    await taskContract.getOwner()

    ui.write('Waiting for counter to increase...');

    let seqnoAfter = await taskContract.getSeqno();
    let attempt = 1;
    while (seqnoAfter === seqnoBefore) {
        ui.setActionPrompt(`Attempt ${attempt}`);
        await sleep(2000);
        seqnoAfter = await taskContract.getSeqno();
        attempt++;
    }

    ui.clearActionPrompt();
}
