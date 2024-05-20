import { Address, toNano } from '@ton/core';
import { TaskContract } from '../wrappers/TaskContract';
import { NetworkProvider, sleep } from '@ton/blueprint';

export async function run(provider: NetworkProvider, args: string[]) {
    const ui = provider.ui();

    const address = Address.parse(args.length > 0 ? args[0] : await ui.input('TaskContract address'));

    if (!(await provider.isContractDeployed(address))) {
        ui.write(`Error: Contract at address ${address} is not deployed!`);
        return;
    }

    const taskContract = provider.open(TaskContract.fromAddress(address));

    let solvers = await taskContract.getSolvers();

    ui.clearActionPrompt();
}
