import { Address, toNano } from '@ton/core';
import { TaskContract } from '../wrappers/TaskContract';
import { NetworkProvider, sleep } from '@ton/blueprint';

export async function run(provider: NetworkProvider, args: string[]) {
    const ui = provider.ui();

    const address = Address.parse(args.length > 0 ? args[0] : await ui.input('TaskContract address'));
    const seqno = BigInt(args.length > 1 ? args[1] : await ui.input('Seqno'));

    if (!(await provider.isContractDeployed(address))) {
        ui.write(`Error: Contract at address ${address} is not deployed!`);
        return;
    }

    const taskContract = provider.open(TaskContract.fromAddress(address));

    const seqnoBefore = await taskContract.getSeqno();
    
    ui.write('Bruteforce begin...');
    // const tasks = [];
    for (let i = 500; i > 0; i--) {
        // tasks.push(
        let res = false;
        try {
        let res = await taskContract.send(
                provider.sender(),
                {
                    value: toNano('0.01'),
                },
                {
                    $$type: 'CheckKey',
                    guessedKey: BigInt(i),
                    seqno: seqno,
                }
            )
            .then(() => taskContract.getSeqno())
            .then(seq => {
                if (seq !== seqnoBefore) {
                    ui.write(`Key was found at ${i}`);
                    return true;
                }
                return false;
            })
        // )
        ;
        } catch (error) {
            await setTimeout(() => null, 3000);
            i++;
            ui.write(`Error at ${i}`);
        }
        if(res) break;
    }

    // let res = await Promise.all(tasks);
    
    ui.write('Bruteforce end');
    let seqnoAfter = await taskContract.getSeqno();
    if (seqnoBefore === seqnoAfter) {
        ui.write("Error, key not found");
    }
    ui.clearActionPrompt();
}
