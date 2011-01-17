using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Xml;
using System.Xml.Serialization;
using System.Runtime.Serialization.Formatters.Soap;
using System.IO;

namespace xDiffPatcher
{

    public partial class frmMain : Form
    {
        public DiffFile file;
        private KeyValuePair<string, DiffPatch>[] indexedPatches;

        FileInfo exeFile = null;
        FileInfo diffFile = null;
        
        public int[] leftPatches;
        public int[] rightPatches;

        public frmMain()
        {
            InitializeComponent();
        }



        private void btnOpenExe_Click(object sender, EventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.Filter = "Exe File (*.exe)|*.exe";
            ofd.CheckFileExists = true;

            if (ofd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
            {
                txtExeFile.Text = ofd.FileName;

                exeFile = new FileInfo(ofd.FileName);

                DirectoryInfo di = exeFile.Directory;
                foreach (FileInfo fi in di.GetFiles())
                    if (fi.Name.ToLower() == exeFile.Name.ToLower().Replace(".exe", ".xdiff"))
                    {
                        diffFile = fi;
                        txtDiffFile.Text = fi.FullName;
                    }
            }
        }

        private void btnOpenDiff_Click(object sender, EventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.Filter = "xDiff File (*.xdiff)|*.xdiff";
            ofd.CheckFileExists = true;

            if (ofd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
            {
                txtDiffFile.Text = ofd.FileName;
            }
        }

        private void btnLoad_Click(object sender, EventArgs e)
        {
            file = new DiffFile();
            if (txtDiffFile.Text.EndsWith(".xdiff"))
                file.Load(txtDiffFile.Text, DiffType.xDiff);
            else
                file.Load(txtDiffFile.Text, DiffType.Diff);

            lstPatches.Nodes.Clear();

            int num = file.PatchCount();

            if (file.Type == DiffType.xDiff)
            {
                leftPatches = new int[num];
                rightPatches = new int[num];
            }
            else
            {
                indexedPatches = file.Patches.ToArray();

                leftPatches = new int[file.Patches.Count];
                rightPatches = new int[file.Patches.Count];
            }

            int i= 0;

            for (i = 0; i < leftPatches.Length; i++)
            {
                leftPatches[i] = int.MaxValue;
                rightPatches[i] = int.MaxValue;
            }

            i = 0;
            if (file.Type == DiffType.xDiff)
                foreach (KeyValuePair<int,DiffPatchBase> p in file.xPatches)
                {
                    if (p.Value is DiffPatchGroup)
                    {
                        TreeNodeEx node = new TreeNodeEx(p.Value.Name);
                        node.Tag = file.xPatches[p.Value.ID]; //is this a reference or a copy? :x
                        node.ActAsRadioGroup = true;

                        foreach (DiffPatch p2 in ((DiffPatchGroup)p.Value).Patches)
                        {
                            TreeNodeEx n = new TreeNodeEx(p2.Name);
                            n.Tag = file.xPatches[p2.ID];
                            node.Nodes.Add(n);
                        }
                        lstPatches.Nodes.Add(node);
                    }
                    else if (p.Value is DiffPatch && ((DiffPatch) p.Value).GroupID <= 0)
                    {
                        TreeNodeEx node = new TreeNodeEx(p.Value.Name);
                        node.Tag = file.xPatches[p.Value.ID];
                        lstPatches.Nodes.Add(node);
                    }
                }
            else
                foreach (KeyValuePair<string, DiffPatch> p in indexedPatches)
                {
                    leftPatches[i] = i;
                    //lstPatches.Items.Add(p.Value.Name);
                    i++;
                }
        }

        private void lstPatches_MouseDown(ListBox sender, MouseEventArgs e)
        {
            /*int indexOfItem = sender.IndexFromPoint(e.X, e.Y);

            if (indexOfItem >= 0 && indexOfItem < sender.Items.Count && sender.SelectedIndices.Contains(indexOfItem))
            {
                sender.DoDragDrop(sender.SelectedItems, DragDropEffects.Copy);
            }*/
        }

        private void lstPatches_DragEnter(ListBox sender, DragEventArgs e)
        {
            if (e.Data.GetDataPresent(DataFormats.StringFormat) && (e.AllowedEffect == (DragDropEffects.Copy | DragDropEffects.Move)))
                e.Effect = DragDropEffects.Copy;
            else
                e.Effect = DragDropEffects.Move;
        }

        private void lstPatches_DragOver(ListBox sender, DragEventArgs e)
        {
            MessageBox.Show((string)e.Data.GetData(DataFormats.StringFormat));
        }

        private void RebuildListboxes()
        {
            lstPatches.Nodes.Clear();

            Array.Sort(leftPatches);
            Array.Sort(rightPatches);

            int i = 0;
            /*foreach (int str in leftPatches)
            {
                if (str == int.MaxValue)
                    continue;
                //lstPatches1.Items.Add(indexedPatches[str].Value.Name);
                lstPatches.Items.Add("(" + ((DiffPatch)file.xPatches[str]).GroupID + ") " + file.xPatches[str].Name);
                i++;
            }

            foreach (int str in rightPatches)
            {
                if (str == int.MaxValue)
                    continue;
                //lstPatches2.Items.Add(indexedPatches[str].Value.Name);
                lstPatches2.Items.Add("(" + ((DiffPatch)file.xPatches[str]).GroupID + ") " + file.xPatches[str].Name);
                i++;
            }*/
        }

        private void btnToLeft_Click(object sender, EventArgs e)
        {
            /*if (lstPatches2.SelectedItems == null || lstPatches2.SelectedItems.Count <= 0)
                return;

            int leftCount = lstPatches.Items.Count;
            int i = 0;
            foreach (int idx in lstPatches2.SelectedIndices)
            {
                leftPatches[leftCount + i] = rightPatches[idx];
                rightPatches[idx] = int.MaxValue;
                i++;
            }

            int j = 0;

            Array.Sort(rightPatches);
            Array.Sort(leftPatches);

            RebuildListboxes();*/
        }


        private bool CanMoveToRight(int pid)
        {
            DiffPatch p = (DiffPatch)file.xPatches[pid];
            if (p.GroupID <= 0)
                return true;

            foreach (int idx in rightPatches)
            {
                if (idx != int.MaxValue && file.xPatches.ContainsKey(idx))
                {
                    DiffPatch p2 = (DiffPatch)file.xPatches[idx];
                    if (p2.GroupID == p.GroupID)
                        return false;
                }
            }

            return true;
        }

        private void btnToRight_Click(object sender, EventArgs e)
        {
            /*if (lstPatches.SelectedItems == null || lstPatches.SelectedItems.Count <= 0)
                return;

            int rightCount = lstPatches2.Items.Count;
            int i = 0;
            foreach (int idx in lstPatches.SelectedIndices)
            {  
                if (!CanMoveToRight(leftPatches[idx]))
                {
                    DiffPatch p = (DiffPatch)file.xPatches[leftPatches[idx]];
                    MessageBox.Show("You can only apply one patch of the \""+file.xPatches[p.GroupID].Name+"\" group!");

                    Array.Sort(leftPatches);
                    Array.Sort(rightPatches);
                    RebuildListboxes();
                    return;
                }
                rightPatches[rightCount + i] = leftPatches[idx];
                leftPatches[idx] = int.MaxValue;
                i++;
            }


            Array.Sort(leftPatches);
            Array.Sort(rightPatches);
            RebuildListboxes();*/
        }

        private void btnSave_Click(object sender, EventArgs e)
        {
            SaveFileDialog sfd = new SaveFileDialog();
            sfd.Filter = "Exe file (*.exe)|*.exe";
            sfd.FileName = file.FileInfo.Name.Replace(".exe", "_patched.exe");
            sfd.DefaultExt = "*.exe";
            sfd.OverwritePrompt = true;
            sfd.InitialDirectory = new System.IO.FileInfo(txtExeFile.Text).DirectoryName;

            if (sfd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
            {
                System.IO.FileStream str = new System.IO.FileStream("lastPatches.xml", System.IO.FileMode.Create, System.IO.FileAccess.Write); 
                SoapFormatter soap = new SoapFormatter();
                IEnumerable<int> patches = rightPatches.Where(patch => patch != int.MaxValue);
                soap.Serialize(str, patches.ToArray<int>());
                str.Close();

                int i = 0;
                //foreach (KeyValuePair<int, DiffPatchBase> p in file.xPatches)
                //{
                //    if (p.Value is DiffPatchGroup)
                //        continue;
                //    /*if (rightPatches.Contains(p.Key))
                //        ((DiffPatch)file.xPatches[p.Key]).Apply = true;
                //    else
                //        ((DiffPatch)file.xPatches[p.Key]).Apply = false;*/
                //}

                foreach (TreeNode n in lstPatches.Nodes)
                {
                    if (n.Tag is DiffPatch)
                        ((DiffPatch)n.Tag).Apply = n.Checked;

                    foreach (TreeNode m in n.Nodes)
                    {
                        if (m.Tag is DiffPatch)
                            ((DiffPatch)m.Tag).Apply = m.Checked;
                    }
                }

                /*foreach (KeyValuePair<string, DiffPatch> p in indexedPatches)
                {
                    if (rightPatches.Contains(i))
                        file.Patches[p.Key].Apply = true;
                    else
                        file.Patches[p.Key].Apply = false;
                    i++;
                }*/

                file.Patch(txtExeFile.Text, sfd.FileName);
            }
        }

        private void mnuProfiles_Click(object sender, EventArgs e)
        {
            new frmProfiles().ShowDialog();
        }

        private void btnApplyLast_Click(object sender, EventArgs e)
        {
            System.IO.FileStream str = null;
            SoapFormatter soap = new SoapFormatter();
            int[] lastPatches;

            try
            {
                str = new System.IO.FileStream("lastPatches.xml", System.IO.FileMode.Open, System.IO.FileAccess.Read);
                lastPatches = (int[])soap.Deserialize(str);
                str.Close();

                foreach (int n in lastPatches)
                {
                    if (!rightPatches.Contains(n))
                    {
                        rightPatches[rightPatches.ToList().IndexOf(int.MaxValue)] = n;
                        leftPatches[leftPatches.ToList().IndexOf(n)] = int.MaxValue;
                    }
                }

                RebuildListboxes();
            }
            catch (System.IO.FileNotFoundException)
            {
                MessageBox.Show("No last used patches found!");
                return;
            }
            catch (Exception ex)
            {
                MessageBox.Show("Error deserializing last patches:\n" + ex.ToString());
                return;
            }
            finally
            {
                if (str != null)
                    str.Close();
            }

        }

        private void ShowModifier(int i)
        {
            if (lstPatches.SelectedNode != null && lstPatches.SelectedNode.Tag != null && lstPatches.SelectedNode.Tag is DiffPatch)
            {
                var p = (DiffPatch)lstPatches.SelectedNode.Tag;
                if (p.Inputs.Count <= 0 || i >= p.Inputs.Count)
                {
                    //txtModifier.Visible = true;
                    //cmbModifiers.Visible = true;
                    //picModifier.Visible = true;
                    //lblModifiers.Visible = true;

                    return;
                }

                //txtModifier.Visible = true;
                //cmbModifiers.Visible = true;
                //picModifier.Visible = true;
                //lblModifiers.Visible = true;

                txtModifier.Text = p.Inputs[i].Value;
            }
            else
            {
                /*txtModifier.Visible = true;
                cmbModifiers.Visible = true;
                picModifier.Visible = true;
                lblModifiers.Visible = true;*/
            }
        }

        private void lstPatches_AfterSelect(object sender, TreeViewEventArgs e)
        {
            if (e.Node != null && e.Node.Tag != null && e.Node.Tag is DiffPatchBase)
            {
                cmbModifiers.Items.Clear();
                txtModifier.Text = "";
                picModifier.Image = null;

                ShowModifier(0);

                if (e.Node.Tag is DiffPatchGroup)
                {
                    var p = (DiffPatchGroup)e.Node.Tag;
                    txtDesc.Text = "[" + p.Name + "]";
                }
                else if (e.Node.Tag is DiffPatch)
                {
                    var p = (DiffPatch)e.Node.Tag;
                    txtDesc.Text = "[" + p.Type + "] " + p.Name + "\n" + p.Desc;

                    foreach (DiffInput i in p.Inputs)
                    {
                        cmbModifiers.Items.Add(i.Name.TrimStart('$'));
                    }
                    if (p.Inputs.Count > 0) {
                        cmbModifiers.SelectedIndex = 0;
                        txtModifier_TextChanged(null, null);
                    }
                    //ShowModifier(0);
                }
                else
                {
                    txtDesc.Text = "%%ERROR%%";
                }
            }
        }



        private void txtModifier_TextChanged(object sender, EventArgs e)
        {
            if (lstPatches.SelectedNode != null && lstPatches.SelectedNode.Tag != null && lstPatches.SelectedNode.Tag is DiffPatch && cmbModifiers.SelectedIndex >= 0)
            {
                DiffInput input = ((DiffPatch)lstPatches.SelectedNode.Tag).Inputs[cmbModifiers.SelectedIndex];

                bool ok = DiffInput.CheckInput(txtModifier.Text, input);
                
                if (ok)
                    input.Value = txtModifier.Text;

                if (!ok)
                    picModifier.Image = imgListModifier.Images["red.png"];
                else
                    picModifier.Image = imgListModifier.Images["green.png"];
            }
        }

        private void cmbModifiers_SelectedIndexChanged(object sender, EventArgs e)
        {
            ShowModifier(cmbModifiers.SelectedIndex);
        }

    }

    public class MyPatch : DiffPatch
    {
        
    }

    public class MyPatchGroup : DiffPatchGroup
    {
    }

}
