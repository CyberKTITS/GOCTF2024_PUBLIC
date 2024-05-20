import * as fs from "fs";
import * as path from "path";
import { Address, contractAddress } from "@ton/core";
import { TaskContract } from "../build/TaskContract/tact_TaskContract";
import { prepareTactDeployment } from "@tact-lang/deployer";

export async function run() {
    // Parameters
    let testnet = true;
    let packageName = "../build/TaskContract/tact_TaskContract.pkg";
    let id = BigInt(Math.floor(Math.random() * 10000));
    let init = await TaskContract.init(id);

    // Load required data
    let address = contractAddress(0, init);
    let data = init.data.toBoc();
    let pkg = fs.readFileSync(path.resolve(__dirname, packageName));

    // Prepareing
    console.log("Uploading package...");
    let prepare = await prepareTactDeployment({ pkg, data, testnet });

    // Deploying
    console.log("============================================================================================");
    console.log("Contract Address");
    console.log("============================================================================================");
    console.log();
    console.log(address.toString({ testOnly: testnet }));
    console.log();
    console.log("============================================================================================");
    console.log("Contract Id");
    console.log("============================================================================================");
    console.log();
    console.log(id);
    console.log();
    console.log("============================================================================================");
    console.log("Please, follow deployment link");
    console.log("============================================================================================");
    console.log();
    console.log(prepare);
    console.log();
    console.log("============================================================================================");
}